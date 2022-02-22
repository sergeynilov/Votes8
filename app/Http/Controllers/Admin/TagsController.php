<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use ImageOptimizer;

use App\User;
use App\Vote;
use App\TagDetail;
use App\MyTag;
use App\Taggable as MyTaggable;
use App\Tag as SpatieTag;  //vendor/spatie/laravel-tags/src/Tag.php

use App\Http\Controllers\MyAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use App\Http\Traits\funcsTrait;

use App\Http\Requests\TagRequest;

class TagsController extends MyAppController
{
    use funcsTrait;

    // TAGS LISTING/EDITOR BLOCK BEGIN

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter_type = '', $filter_value = '')
    {
        $viewParamsArray                 = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'], ['filter_type' => $filter_type, 'filter_value' => $filter_value]);
        $viewParamsArray['filter_type']  = $filter_type;
        $viewParamsArray['filter_value'] = $filter_value;

        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);
        return view($this->getBackendTemplateName() . '.admin.tag.index', $viewParamsArray);
    }

    public function get_tags_dt_listing()
    {
        $request     = request();
        $filter_name = $request->input('filter_name');

        $tagsCollection = MyTag::containingName($filter_name)->get();

        foreach( $tagsCollection as $next_key=> $nextTag ) {
            $tagsCollection[$next_key]->slashed_name= addslashes( $this->getSpatieTagLocaledValue($nextTag->name) );
        }

        return Datatables
            ::of($tagsCollection)
            ->editColumn('name', function ($tag) {
                return isset($tag->name) ? $this->getSpatieTagLocaledValue($tag->name) : '';
            })
            ->editColumn('slug', function ($tag) {
                return isset($tag->slug) ? $this->getSpatieTagLocaledValue($tag->slug) : '';
            })
            ->editColumn('created_at', function ($tag) {
                if (empty($tag->created_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($tag->created_at);
            })
            ->editColumn('updated_at', function ($tag) {
                if (empty($tag->updated_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($tag->updated_at);
            })
            ->editColumn('action', '<a href="/admin/tags/{{$id}}/edit"><i class=" fa fa-edit"></i></a>')
            ->editColumn('action_delete',
                '<a href="#" onclick="javascript:backendTag.deleteTag({{$id}},\'{{$slashed_name}}\')"><i class="fa fa-remove a_link"></i></a>')
            ->rawColumns(['action', 'action_delete'])
            ->make(true);
    }

    public function create()
    {
        $viewParamsArray                        = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.tag.create', $viewParamsArray);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        $requestData = $request->all();
        $tagDetailUploadFile = $request->file('image');
        $uploaded_file_max_mib = (int)\Config::get('app.uploaded_file_max_mib', 1);
        $max_size              = 1024 * $uploaded_file_max_mib;
        $rules                 = array(
            'image' => 'max:' . $max_size,
        );
        $validator             = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator);
        }

        $tag_detail_image       = '';
        $tag_file_path          = '';
//        echo '<pre>$requestData::'.print_r($requestData,true).'</pre>';
        $tag_detail_description = $requestData['description'];
        $tag_detail_meta_description = !empty($requestData['tag_meta_description']) ? $requestData['tag_meta_description'] : '';
        if ( ! empty($tagDetailUploadFile)) {
            $tag_detail_image = TagDetail::checkValidImgName($tagDetailUploadFile->getClientOriginalName(), with(new TagDetail)->getImgFilenameMaxLength(), true);
            $tag_file_path    = $tagDetailUploadFile->getPathName();
        }

        DB::beginTransaction();
        try {
            $newTag = SpatieTag::findOrCreate($requestData['name'], with(new MyTag)->getVotesTagType());

            if ( ! empty($tag_detail_image) or !empty($tag_detail_description) OR !empty($tag_detail_meta_description) ) {
                $tagDetail              = new TagDetail();
                $tagDetail->tag_id      = $newTag->id;
                if ( !empty($tag_file_path) ) {
                    $tagDetail->image = $tag_detail_image;
                }
                $tagDetail->description = $tag_detail_description;
                $tagDetail->meta_description = $tag_detail_meta_description;
                $tagDetail->save();
                if ( !empty($tag_file_path) ) {
                    $dest_image = 'public/' . TagDetail::getTagDetailImagePath($tagDetail->tag_id, $tag_detail_image);
                    Storage::disk('local')->put($dest_image, File::get($tag_file_path));
                    ImageOptimizer::optimize( storage_path().'/app/'.$dest_image, null );
                }
            } // if ( !empty($tag_detail_image) ) {

            if ( ! empty($requestData['order_column'])) {
                $newTag->order_column = $requestData['order_column'];
                $newTag->save();
            }
            DB::commit();
//            die("-1 XXZ===");
        } catch (\Exception $e) {
            DB::rollback();
//            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput(['name' => $request->get('name'), 'status' => $request->get('status')]);
        }
        $this->setFlashMessage('Tag created successfully !', 'success', 'Backend');

        return redirect('admin/tags/' . $newTag->id . "/edit");//->route('admin.tags.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($tag_id)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $tag = MyTag::find($tag_id);
        if ($tag === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', ['text' => 'Tag with id # "' . $tag_id . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }
        $tag->name       = $this->getSpatieTagLocaledValue($tag->name);
        $tag->slug       = $this->getSpatieTagLocaledValue($tag->slug);

        $tagDetail = TagDetail::getByTagId($tag->id)->first();
//        echo '<pre>$tagDetail::'.print_r($tagDetail,true).'</pre>';
        if ( ! empty($tagDetail)) {
            $tagDetailImageProps = TagDetail::setTagDetailImageProps($tagDetail->tag_id, $tagDetail->image, true);
            if (count($tagDetailImageProps) > 0) {
                $tagDetail->setTagDetailImagePropsAttribute($tagDetailImageProps);
            }
        }
        $viewParamsArray['tag']                 = $tag;
        $viewParamsArray['tagDetail']           = $tagDetail;
        $appParamsForJSArray['id']              = $tag_id;
        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.tag.edit', $viewParamsArray);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Tag $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $request, int $tag_id)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $requestData = $request->all();

        $tag = MyTag::find($tag_id);
        if ($tag === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', ['text' => 'Tag with id # "' . $tag_id . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }
        $tagDetail = TagDetail::getByTagId($tag->id)->first();

        $tagDetailUploadFile   = $request->file('image');
        $uploaded_file_max_mib = (int)\Config::get('app.uploaded_file_max_mib', 1);
        $max_size              = 1024 * $uploaded_file_max_mib;
        $rules                 = array(
            'image' => 'max:' . $max_size,
        );
        $validator             = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator);
        }

        $tag->updated_at = Carbon::now(config('app.timezone'));

        $tag_detail_image= '';
//        echo '<pre>$requestData::'.print_r($requestData,true).'</pre>';
        $tag_detail_description      = $requestData['description'];
        $tag_detail_meta_description = !empty($requestData['tag_meta_description']) ? $requestData['tag_meta_description'] : '';
        if ( ! empty($tagDetailUploadFile)) {
            $tag_detail_image     = TagDetail::checkValidImgName($tagDetailUploadFile->getClientOriginalName(), with(new TagDetail)->getImgFilenameMaxLength(), true);
            $tag_file_path = $tagDetailUploadFile->getPathName();
            $tag->image    = $tag_detail_image;
            $dest_image = 'public/' . TagDetail::getTagDetailImagePath($tag_id, $tag_detail_image);
            Storage::disk('local')->put($dest_image, File::get($tag_file_path));
            ImageOptimizer::optimize( storage_path().'/app/'.$dest_image, null );
        } //             if ( !empty($tag_detail_image) ) {

        DB::beginTransaction();
        try {
            if ( empty($tagDetail)) {
                $tagDetail              = new TagDetail();
                $tagDetail->tag_id      = $tag->id;
            }
            if (!empty($tag_detail_image)) {
                $tagDetail->image = $tag_detail_image;
            }
//            echo '<pre>$tag_detail_description::'.print_r($tag_detail_description,true).'</pre>';
//            echo '<pre>$tag_detail_meta_description::'.print_r($tag_detail_meta_description,true).'</pre>';
            $tagDetail->description     = $tag_detail_description;
            $tagDetail->meta_description     = $tag_detail_meta_description;
            $tagDetail->save();

            DB::commit();
//            die("-1 XXZ");
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }
        $this->setFlashMessage('Tag updated successfully !', 'success', 'Backend');

        return redirect()->route('admin.tags.index');
    }


    /* delete item with related tag */
    public function destroy(Request $request)
    {
        $id  = $request->get('id');
        $tag = MyTag::find($id);

        if ($tag === null) {
            return response()->json(['error_code' => 11, 'message' => 'Tag # "' . $id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $tag->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function destroy(Request $request)


    public function get_tag_details_info(int $tag_id)
    {
        $tag                 = MyTag::find($tag_id);
        $tempTagRelatedVotes = MyTaggable
            ::getByTagId($tag->id)
            ->getByTaggableType(\App\Vote::class)
            ->get();
        $tagRelatedVotes = [];
        foreach ($tempTagRelatedVotes as $next_key => $nextTempTagRelatedVote) {
            $nextVote = Vote
                ::where(with(new Vote)->getTable() . '.id', $nextTempTagRelatedVote->taggable_id)
                ->leftJoin(\DB::raw('vote_categories'), \DB::raw('vote_categories.id'), '=', \DB::raw('votes.vote_category_id'))
                ->select(\DB::raw(" votes.*, votes.image as vote_image, vote_categories.name as vote_category_name, vote_categories.slug as vote_category_slug"))
                ->first();
            if (empty($nextVote)) {
                continue;
            }

            $voteImageProps = Vote::setVoteImageProps($nextTempTagRelatedVote->taggable_id, $nextVote->image, false);
            if (count($voteImageProps) > 0) {
                $nextVote->setVoteImagePropsAttribute($voteImageProps);
            }

            $tagRelatedVotes[] = $nextVote;
        }
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['tag_id']    = $tag_id;
        $viewParamsArray['tag']       = $tag;
        $tagDetail                    = TagDetail::getByTagId($tag->id)->first();
        if ( ! empty($tagDetail)) {
            $tagDetailImageProps      = TagDetail::setTagDetailImageProps($tagDetail->tag_id, $tagDetail->image, true);
            if (count($tagDetailImageProps) > 0) {
                $tagDetail->setTagDetailImagePropsAttribute($tagDetailImageProps);
            }
        }

        $viewParamsArray['tagDetail'] = $tagDetail;

        $viewParamsArray['tagRelatedVotes'] = $tagRelatedVotes;
        $html                               = view($this->getBackendTemplateName() . '.admin.tag.tag_details_info', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_tag_details_info(Request $request)

    // TAGS LISTING/EDITOR BLOCK END



    // TAG META KEYWORD BLOCK BEGIN
    public function get_tag_meta_keywords(int $tag_id)
    {
        $tag                          = MyTag::find($tag_id);
        //$tagDetail                    = TagDetail::find($tag_id);
        $tagDetail                    = TagDetail::getByTagId($tag->id)->first();
        $metaKeywords                 = !empty($tagDetail->meta_keywords) ? $tagDetail->meta_keywords : [];
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['tag_id']    = $tag_id;
        $viewParamsArray['tagDetail']       = $tagDetail;
        $viewParamsArray['metaKeywords']  = $metaKeywords;
        $html                         = view($this->getBackendTemplateName() . '.admin.tag.meta_keywords', $viewParamsArray)->render();
        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
        // resources/views/defaultBS41Backend/admin/tag/meta_keywords.blade.php

    } // public function META KEYWORD(Request $request)

    public function attach_meta_keyword($tag_id, $meta_keyword)
    {

        $tag = MyTag::find($tag_id);
        if ($tag === null) {
            return response()->json(['error_code' => 11, 'message' => 'Tag # "' . $tag_id . '" not found!'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        
//        $tagDetail = TagDetail::find($tag_id);
        $tagDetail = TagDetail::getByTagId($tag->id)->first();
//        if ($tagDetail === null) {
//            return response()->json(['error_code' => 11, 'message' => 'Tag # "' . $tag_id . '" not found!'],
//                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
//        }
        DB::beginTransaction();
        try {
            $meta_keywords_array= !empty($tagDetail->meta_keywords) ? $tagDetail->meta_keywords : [];
            if ( in_array($meta_keyword, $meta_keywords_array) ) {
                return response()->json(['error_code' => 11, 'message' => 'This tag already has "' . $meta_keyword . '" !'],
                    HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }
            $meta_keywords_array[]= $meta_keyword;
            $tagDetail->meta_keywords= $meta_keywords_array;
            $tagDetail->save();

            $tag->updated_at= Carbon::now(config('app.timezone'));
            $tag->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'tag' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function attach_meta_keyword($tag_id, $tag_id)

    public function clear_meta_keyword($tag_id, $meta_keyword)
    {
        $tag = MyTag::find($tag_id);
        if ($tag === null) {
            return response()->json(['error_code' => 11, 'message' => 'Tag # "' . $tag_id . '" not found!'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

//        $tagDetail        = TagDetail::find($tag_id);
        $tagDetail = TagDetail::getByTagId($tag->id)->first();
        if ($tagDetail === null) {
            return response()->json(['error_code' => 11, 'message' => 'Tag Detail # "' . $tag_id . '" not found!' ],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {

            $meta_keywords_array= $tagDetail->meta_keywords;
            foreach( $meta_keywords_array as $next_key=>$next_value ) {
                if ( $next_value == $meta_keyword ) {
                    unset($meta_keywords_array[$next_key]);
                }
            }
            $tagDetail->meta_keywords= $meta_keywords_array;
            $tagDetail->save();

            $tag->updated_at= Carbon::now(config('app.timezone'));
            $tag->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json( ['error_code' => 1, 'message' => $e->getMessage() ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function clear_meta_keyword($tag_id, $meta_keyword)  */


    public function update_tag_meta_description($tag_id, $meta_description)
    {
        $tagDetail = MyTag::find($tag_id);
        if ($tagDetail === null) {
            return response()->json(['error_code' => 11, 'message' => 'Tag # "' . $tag_id . '" not found!' ],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {

            if( trim($meta_description) == '-' ) $meta_description= '';
            $tagDetail->meta_description= $meta_description;
            $tagDetail->updated_at= Carbon::now(config('app.timezone'));
            $tagDetail->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json( ['error_code' => 1, 'message' => $e->getMessage() ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function update_tag_meta_description($tag_id, $meta_description)  */

    // TAG META KEYWORD BLOCK END

}
