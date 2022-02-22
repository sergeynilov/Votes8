<?php

namespace App\Http\Controllers\Admin;

use App\VoteCategory;
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
use App\VoteItem;
use App\Http\Controllers\MyAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use App\Http\Traits\funcsTrait;
use App\Http\Requests\VoteRequest;
use App\Http\Requests\VoteItemRequest;
use App\MyTag;
use App\Taggable as MyTaggable;
use Spatie\Tags\Tag as SpatieTag;

class VotesController extends MyAppController
{
    use funcsTrait;
    private $votes_tb;
    private $vote_items_tb;
    private $vote_categories_tb;
//    private $vote_item_users_results_tb;

    public function __construct()
    {
        $this->votes_tb= with(new Vote)->getTable();
        $this->vote_items_tb= with(new VoteItem)->getTable();
        $this->vote_categories_tb= with(new VoteCategory())->getTable();
//        $this->vote_item_users_results_tb= with(new VoteItemUsersResult())->getTable();
    }


    // VOTE LISTING/EDITOR BLOCK BEGIN

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter_type = '', $filter_value = '')
    {
        $viewParamsArray                                 = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'],
            ['filter_type' => $filter_type, 'filter_value' => $filter_value]);
        $viewParamsArray['voteIsQuizValueArray']         = $this->SetArrayHeader(['' => ' -Select Is Quiz- '], Vote::getVoteIsQuizValueArray(false));
        $viewParamsArray['voteStatusValueArray']         = $this->SetArrayHeader(['' => ' -Select Status- '], Vote::getVoteStatusValueArray(false));
        $viewParamsArray['voteIsHomepageValueArray']     = $this->SetArrayHeader(['' => ' -Select Is Homepage- '], Vote::getVoteIsHomepageValueArray(false));
        $voteCategoriesSelectionArray                    = VoteCategory::getVoteCategoriesSelectionArray();
        $viewParamsArray['voteCategoriesSelectionArray'] = $this->SetArrayHeader(['' => ' -Select Vote Category- '], $voteCategoriesSelectionArray);
        $viewParamsArray['filter_type']                  = $filter_type;
        $viewParamsArray['filter_value']                 = $filter_value;

        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);
        return view($this->getBackendTemplateName() . '.admin.vote.index', $viewParamsArray);
    }

    public function get_votes_dt_listing()
    {
        $request = request();

        $filter_name             = $request->input('filter_name', '');
        $filter_status           = $request->input('filter_status', '');
        $filter_is_quiz          = $request->input('filter_is_quiz', '');
        $filter_is_homepage      = $request->input('filter_is_homepage', '');
        $filter_vote_category_id = $request->input('filter_vote_category_id', '');

        $votesCollection = Vote
            ::getByName($filter_name, true)
            ->getByStatus($filter_status, true)
            ->getByIsQuiz($filter_is_quiz, true)
            ->getByIsHomepage($filter_is_homepage, true)
            ->getByVoteCategory($filter_vote_category_id, true)
            ->leftJoin( $this->vote_categories_tb , $this->vote_categories_tb.'.id', '=', $this->votes_tb.'.vote_category_id' )
            ->select( $this->votes_tb.".*", $this->vote_categories_tb.".name as vote_category_name" )
            ->get();

        foreach( $votesCollection as $next_key=> $nextVote ) {
            $votesCollection[$next_key]->slashed_name= addslashes($nextVote->name);
        }

        return Datatables
            ::of($votesCollection)
            ->editColumn('status', function ($vote) {
                if ( ! isset($vote->status)) {
                    return '::' . $vote->status;
                }
                return Vote::getVoteStatusLabel($vote->status);
            })

            ->setRowClass(function ($vote) {
                return $vote->status == 'N' ? ' row_new_status' : ($vote->status == 'I' ? 'row_inactive_status' : '');
            })

            ->editColumn('is_quiz', function ($vote) {
                if ( ! isset($vote->is_quiz)) {
                    return '::' . $vote->is_quiz;
                }
                return Vote::getVoteIsQuizLabel($vote->is_quiz);
            })

            ->editColumn('is_homepage', function ($vote) {
                if ( ! isset($vote->is_homepage)) {
                    return '::' . $vote->is_homepage;
                }
                return Vote::getVoteIsHomepageLabel($vote->is_homepage);
            })

            ->editColumn('created_at', function ($vote) {
                if (empty($vote->created_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($vote->created_at);
            })

            ->editColumn('updated_at', function ($vote) {
                if (empty($vote->updated_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($vote->updated_at);
            })

            ->editColumn('action', '<a href="/admin/votes/{{$id}}/edit"><i class=" fa fa-edit"></i></a>')
            ->editColumn('action_delete',
                '<a href="#" onclick="javascript:backendVote.deleteVote({{$id}},\'{{$slashed_name}}\')"><i class="fa fa-remove a_link"></i></a>')
            ->rawColumns(['action', 'action_delete'])
            ->make(true);
    }

    public function create()
    {

        $viewParamsArray                      = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['vote']              = new Vote();
        $viewParamsArray['vote']->is_quiz     = true;
        $viewParamsArray['vote']->is_homepage = '0';
        $viewParamsArray['vote']->status      = 'A';

        $viewParamsArray['voteIsQuizValueArray']         = $this->SetArrayHeader(['' => ' -Select Is Quiz- '], Vote::getVoteIsQuizValueArray(false));
        $viewParamsArray['voteStatusValueArray']         = $this->SetArrayHeader(['' => ' -Select Status- '], Vote::getVoteStatusValueArray(false));
        $viewParamsArray['voteIsHomepageValueArray']     = $this->SetArrayHeader(['' => ' -Select Is Homepage- '], Vote::getVoteIsHomepageValueArray(false));
        $voteCategoriesSelectionArray                    = VoteCategory::getVoteCategoriesSelectionArray();
        $viewParamsArray['voteCategoriesSelectionArray'] = $this->SetArrayHeader(['' => ' -Select Vote Category- '], $voteCategoriesSelectionArray);

        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.vote.create', $viewParamsArray);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(VoteRequest $request)
    {
//        echo '<pre>store ::' . print_r(-1, true) . '</pre>';

        $vote     = new Vote();

        $voteUploadFile = $request->file('image');

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

        $loggedUser             = Auth::user();
        $vote->name             = $request->get('name');
        $vote->description      = $request->get('description');
        $vote->creator_id       = $loggedUser->id;
        $vote->vote_category_id = $request->get('vote_category_id');
        $vote->is_quiz          = $request->get('is_quiz');
        $vote->is_homepage      = $request->get('is_homepage');
        $vote->status           = $request->get('status');
        $vote->ordering         = $request->get('ordering');

        if ( ! empty($voteUploadFile)) {
            $vote_image     = Vote::checkValidImgName($voteUploadFile->getClientOriginalName(), with(new Vote)->getImgFilenameMaxLength(), true);
            $vote_file_path = $voteUploadFile->getPathName();
            $vote->image    = $vote_image;
        }

        DB::beginTransaction();
        try {
            $vote->save();

            if ( ! empty($vote_image)) {
                $dest_image = 'public/' . Vote::getVoteImagePath($vote->id, $vote_image);
                Storage::disk('local')->put($dest_image, File::get($vote_file_path));
                ImageOptimizer::optimize( storage_path().'/app/'.$dest_image, null );
            } // if ( !empty($vote_image) ) {

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput(['name' => $request->get('name'), 'status' => $request->get('status')]);
        }
        $this->setFlashMessage('Vote created successfully ! Now you can add vote items/tags.', 'success', 'Backend');

        return redirect('admin/votes/' . $vote->id . '/edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vote $vote
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($vote_id)
    {

        $voteStatusValueArray = $this->SetArrayHeader(['' => ' -Select Status- '], Vote::getVoteStatusValueArray(false));
        $vote                 = Vote::find($vote_id);

        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);


//        echo '<pre>$appParamsForJSArray::'.print_r($appParamsForJSArray,true).'</pre>';

        if ($vote === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', ['text' => 'Vote with id # "' . $vote_id . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }
        $voteImageProps = Vote::setVoteImageProps($vote->id, $vote->image, true);
        if (count($voteImageProps) > 0) {
            $vote->setVoteImagePropsAttribute($voteImageProps);
        }
        $voteItems = VoteItem::getByVote($vote_id)->orderBy('ordering', 'desc')->get();

        $viewParamsArray['vote']                 = $vote;
        $appParamsForJSArray['id']               = $vote_id;
        $viewParamsArray['voteItems']            = $voteItems;
        $viewParamsArray['voteStatusValueArray'] = $voteStatusValueArray;

        $viewParamsArray['voteIsQuizValueArray']         = $this->SetArrayHeader(['' => ' -Select Is Quiz- '], Vote::getVoteIsQuizValueArray(false));
//        echo '<pre>$viewParamsArray[\'voteIsQuizValueArray\']::'.print_r($viewParamsArray['voteIsQuizValueArray'],true).'</pre>';
//        die("-1 XXZ");

        $viewParamsArray['voteStatusValueArray']         = $this->SetArrayHeader(['' => ' -Select Status- '], Vote::getVoteStatusValueArray(false));
        $viewParamsArray['voteIsHomepageValueArray']     = $this->SetArrayHeader(['' => ' -Select Is Homepage- '], Vote::getVoteIsHomepageValueArray(false));
        $voteCategoriesSelectionArray                    = VoteCategory::getVoteCategoriesSelectionArray();
        $viewParamsArray['voteCategoriesSelectionArray'] = $this->SetArrayHeader(['' => ' -Select Vote Category- '], $voteCategoriesSelectionArray);

        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.vote.edit', $viewParamsArray);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Vote $vote
     *
     * @return \Illuminate\Http\Response
     */
    public function update(VoteRequest $request, int $vote_id)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        $vote = Vote::find($vote_id);
        if ($vote === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', ['text' => 'Vote with id # "' . $vote_id . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }
        $voteUploadFile = $request->file('image');

        $uploaded_file_max_mib = (int)\Config::get('app.uploaded_file_max_mib', 1);
        $max_size              = 1024 * $uploaded_file_max_mib;
        $rules                 = array(
            'image' => 'max:' . $max_size,
        );
        $validator             = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        $loggedUser = Auth::user();


        $vote->name             = $request->get('name');
        $vote->description      = $request->get('description');
        $vote->creator_id       = $loggedUser->id;
        $vote->vote_category_id = $request->get('vote_category_id');
        $vote->is_quiz          = $request->get('is_quiz');
        $vote->is_homepage      = $request->get('is_homepage');
        $vote->ordering         = $request->get('ordering');
        $vote->status           = $request->get('status');
        $vote->updated_at       = Carbon::now(config('app.timezone'));


        echo '<pre>$voteUploadFile::'.print_r($voteUploadFile,true).'</pre>';
        if ( ! empty($voteUploadFile)) {
            $vote_image     = Vote::checkValidImgName($voteUploadFile->getClientOriginalName(), with(new Vote)->getImgFilenameMaxLength(), true);
            echo '<pre>$vote_image::'.print_r($vote_image,true).'</pre>';
            $vote_file_path = $voteUploadFile->getPathName();
            echo '<pre>$vote_image::'.print_r($vote_image,true).'</pre>';
            $vote->image    = $vote_image;
        } // if (!empty($voteUploadFile)) {

        if ( ! empty($vote_image)) {
            $dest_image = 'public/' . Vote::getVoteImagePath($vote_id, $vote_image);
            Storage::disk('local')->put($dest_image, File::get($vote_file_path));
            ImageOptimizer::optimize( storage_path().'/app/'.$dest_image, null );
        } //             if ( !empty($vote_image) ) {

        DB::beginTransaction();
        try {
            $vote->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }
        $this->setFlashMessage('Vote updated successfully !', 'success', 'Backend');

        return redirect()->route('admin.votes.index');
    }


    /* delete item with related vote */
    public function destroy(Request $request)
    {
        $id   = $request->get('id');
        $vote = Vote::find($id);

        if ($vote === null) {
            return response()->json(['error_code' => 11, 'message' => 'Vote # "' . $id . '" not found!'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $vote->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function destroy(Request $request)


    public function get_vote_details_info(int $vote_id)
    {
        $vote           = Vote::find($vote_id);
        $voteImageProps = Vote::setVoteImageProps($vote->id, $vote->image, true);
        if (count($voteImageProps) > 0) {
            $vote->setVoteImagePropsAttribute($voteImageProps);
        }

        $voteItems                      = VoteItem::getByVote($vote_id)->orderBy('ordering', 'desc')->get();
        $viewParamsArray                = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['vote_id']     = $vote_id;
        $viewParamsArray['voteItems']   = $voteItems;
        $viewParamsArray['vote']        = $vote;
        $relatedTags                    = $vote->tags;
        $viewParamsArray['relatedTags'] = $relatedTags;
        $html                           = view($this->getBackendTemplateName() . '.admin.vote.vote_details_info', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_vote_details_info(Request $request)

    // VOTE LISTING/EDITOR BLOCK END


    // VOTE ITEMS  LISTING/EDITOR BLOCK BEGIN

    public function get_vote_item_info($vote_item_id)
    {
        $voteItem = VoteItem::find($vote_item_id);
        if ($voteItem === null) {
            return response()->json(['error_code' => 11, 'message' => 'Vote Item# "' . $vote_item_id . '" not found!', 'vote' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return ['message' => '', 'error_code' => 0, 'vote_item_id' => $vote_item_id, 'voteItem' => $voteItem];
    } // public function get_product_affiliated_user()

    public function vote_item_edit($vote_item_id)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        $voteItem = VoteItem::find($vote_item_id);
        if ($voteItem === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg',
                ['text' => 'Vote Item with id # "' . $vote_item_id . '" not found !', 'type' => 'danger', 'action' => ''], $viewParamsArray);
        }
        $voteItemImageProps = VoteItem::setVoteItemImageProps($voteItem->id, $voteItem->image, true);
        if (count($voteItemImageProps) > 0) {
            $voteItem->setVoteItemImagePropsAttribute($voteItemImageProps);
        }


        $parentVote = Vote::find($voteItem->vote_id);
        if ($voteItem === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg',
                ['text' => 'Parent Vote with id # "' . $voteItem->vote_id . '" not found !', 'type' => 'danger', 'action' => ''], $viewParamsArray);
        }

        $parent_vote_id                    = $parentVote->id;
        $viewParamsArray                   = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['voteItem']       = $voteItem;
        $viewParamsArray['parent_vote_id'] = $parent_vote_id;
        $viewParamsArray['parentVote']     = $parentVote;

        $viewParamsArray['voteIsCorrectValueArray'] = $this->SetArrayHeader(['' => ' -Select Is Correct- '], VoteItem::getVoteItemIsCorrectValueArray(false));
        $viewParamsArray['appParamsForJSArray']     = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.vote_item.edit', $viewParamsArray);
    }

    public function vote_item_create($parent_vote_id)
    {

        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        // use App\VoteItem;

        $viewParamsArray['voteItem']             = new voteItem();
        $viewParamsArray['voteItem']->is_correct = false;

        $viewParamsArray['voteIsCorrectValueArray'] = $this->SetArrayHeader(['' => ' -Select Is Correct- '], VoteItem::getVoteItemIsCorrectValueArray(false));

        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);
        $viewParamsArray['parent_vote_id']      = $parent_vote_id;

        return view($this->getBackendTemplateName() . '.admin.vote_item.create', $viewParamsArray);
    }

    // VOTE ITEMS  LISTING/EDITOR BLOCK END

    public function vote_item_store(VoteItemRequest $request)
    {
        $voteItemUploadFile = $request->file('image');

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

        $voteItem             = new VoteItem();
        $voteItem->name       = $request->get('name');
        $voteItem->vote_id    = $request->get('vote_id');
        $voteItem->is_correct = $request->get('is_correct');
        $voteItem->ordering   = $request->get('ordering');


        echo '<pre>$voteItemUploadFile::' . print_r($voteItemUploadFile, true) . '</pre>';
        if ( ! empty($voteItemUploadFile)) {
            $vote_item_image     = VoteItem::checkValidImgName($voteItemUploadFile->getClientOriginalName(), with(new VoteItem)->getImgFilenameMaxLength(), true);
            $vote_item_file_path = $voteItemUploadFile->getPathName();
            $voteItem->image     = $vote_item_image;
        } // if (!empty($voteItemUploadFile)) {

        DB::beginTransaction();
        try {
            $voteItem->save();

            if ( ! empty($vote_item_image)) {
                $dest_image = 'public/' . VoteItem::getVoteItemImagePath($voteItem->id, $vote_item_image);
                Storage::disk('local')->put($dest_image, File::get($vote_item_file_path));
                ImageOptimizer::optimize( storage_path().'/app/'.$dest_image, null );
            } //             if ( !empty($vote_item_image) ) {


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput(['name' => $request->get('name'), 'status' => $request->get('status')]);
        }
        $this->setFlashMessage('Vote item created successfully !', 'success', 'Backend');

//        die("-1 XXZ");
        return redirect('/admin/votes/' . $voteItem->vote_id . '/edit' );
    } //public function vote_item_store(VoteItemRequest $request)


    public function vote_item_update(VoteItemRequest $request, int $vote_item_id)
    {
        echo '<pre>UPDATE $vote_item_id::' . print_r($vote_item_id, true) . '</pre>';
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $voteItem        = VoteItem::find($vote_item_id);
        if ($voteItem === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', [
                'text'   => 'Vote item with id # "' . $vote_item_id . '" not found !',
                'type'   => 'danger',
                'action' => ''
            ], $viewParamsArray);
        }
        $voteItemUploadFile = $request->file('image');

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

        $voteItem->name       = $request->get('name');
        $voteItem->ordering   = $request->get('ordering');
        $voteItem->is_correct = $request->get('is_correct');
        $voteItem->updated_at = Carbon::now(config('app.timezone'));
        /*             $voteItem                    = new VoteItem();
                    $voteItem->name              = $request->get('name');
                    $voteItem->vote_id           = $request->get('vote_id');
                    $voteItem->is_correct        = $request->get('is_correct');
                    $voteItem->ordering          = $request->get('ordering');
         */
        if ( ! empty($voteItemUploadFile)) {
            $vote_image      = VoteItem::checkValidImgName($voteItemUploadFile->getClientOriginalName(), with(new VoteItem)->getImgFilenameMaxLength(), true);
            $vote_file_path  = $voteItemUploadFile->getPathName();
            $voteItem->image = $vote_image;
        } // if (!empty($voteItemUploadFile)) {

        //     public static function getVoteItemImagePath(int $vote_item_id, $image): string
        if ( ! empty($vote_image)) {
            $dest_image = 'public/' . VoteItem::getVoteItemImagePath($vote_item_id, $vote_image);
            Storage::disk('local')->put($dest_image, File::get($vote_file_path));
            ImageOptimizer::optimize( storage_path().'/app/'.$dest_image, null );
        } //             if ( !empty($vote_image) ) {

        DB::beginTransaction();
        try {

            $voteItem->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }
        $this->setFlashMessage('Vote item updated successfully !', 'success', 'Backend');

        return redirect('/admin/votes/' . $voteItem->vote_id . '/edit' );
//        return redirect()->route('admin.votes.index');
    }

    //                 data: { "vote_item_id": id, "_token": this_csrf_token }
//     Route::delete('/vote_items/destroy/{vote_item_id}', 'Admin\VotesController@vote_item_destroy');
    /* delete item with related vote */
    public function vote_item_destroy($vote_item_id)
    {
//        return response()->json(['error_code' => 11, 'message' => 'Vote Item # "' . $vote_item_id . '" not found!', 'voteItem' => null],
//            HTTP_RESPONSE_INTERNAL_SERVER_ERROR);

//           echo '<pre>$vote_item_id::'.print_r($vote_item_id,true).'</pre>';
        $voteItem = VoteItem::find($vote_item_id);

        if ($voteItem === null) {
            return response()->json(['error_code' => 11, 'message' => 'Vote Item # "' . $vote_item_id . '" not found!', 'voteItem' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $voteItem->delete();
            $vote_item_image = VoteItem::getVoteItemDir($vote_item_id);
            $this->deleteDirectory(storage_path('app/public/' . $vote_item_image));

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'voteItem' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);

    } //     public function vote_item_destroy(Request $request)


    // VOTE'S RELATED TAGS BLOCK BEGIN
    public function get_vote_related_tags(int $vote_id)
    {

        $tagsList   = [];
        $tags          = MyTag
            ::orderBy('order_column', 'asc')
            ->get();

        $voteRelatedTags= MyTaggable
            ::getByTaggableId($vote_id)
            ->get();

        foreach( $tags as $next_key=>$nextTag ) {
            $is_tag_selected= false;
            foreach( $voteRelatedTags as $nextVoteRelatedTags ) {
                if ( (int)$nextVoteRelatedTags->tag_id == (int)$nextTag->id ) {
                    $is_tag_selected= 1;
                    break;
                }
            }
            $tagsList[]= [
                'id'=> $nextTag->id,
                'order_column'=> $nextTag->order_column,
                'name'=> $this->getSpatieTagLocaledValue($nextTag->name),
                'slug'=> $this->getSpatieTagLocaledValue($nextTag->slug),
                'created_at'=> $nextTag->created_at,
                'selected'=> $is_tag_selected,
            ];
        }

        $vote                         = Vote::find($vote_id);
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['vote_id']   = $vote_id;
        $viewParamsArray['vote']      = $vote;
        $viewParamsArray['tagsList']  = $tagsList;
        $viewParamsArray['selected_tags_count']  = count($voteRelatedTags);
        $html                         = view($this->getBackendTemplateName() . '.admin.vote.related_tags', $viewParamsArray)->render();
        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_vote_details_info(Request $request)

    public function attach_related_tag($vote_id, $tag_id)
    {
        $vote = Vote::find($vote_id);
        if ($vote === null) {
            return response()->json(['error_code' => 11, 'message' => 'Vote # "' . $vote_id . '" not found!', 'vote' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        $tag = MyTag::find($tag_id);
        if ($tag === null) {
            return response()->json(['error_code' => 11, 'message' => 'Tag # "' . $tag_id . '" not found!', 'tag' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $newTaggable= new MyTaggable();
            $newTaggable->tag_id= $tag_id;
            $newTaggable->taggable_id= $vote_id;
            $newTaggable->taggable_type= \App\Vote::class;
            $newTaggable->save();

            $vote->updated_at= Carbon::now(config('app.timezone'));
            $vote->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'vote' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function attach_related_tag($vote_id, $tag_id)

    public function clear_related_tag($vote_id, $tag_id)
    {
        $vote = Vote::find($vote_id);
        if ($vote === null) {
            return response()->json(['error_code' => 11, 'message' => 'Vote # "' . $vote_id . '" not found!', 'vote' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        $tag = MyTag::find($tag_id);
        if ($tag === null) {
            return response()->json(['error_code' => 11, 'message' => 'Tag # "' . $tag_id . '" not found!', 'tag' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $taggable= MyTaggable
                ::getByTaggableType(\App\Vote::class)
                ->getByTaggableId($vote_id)
                ->getByTagId($tag_id)
                ->first();
            if ($taggable!= null) {
                $taggable->delete();
            }
            $vote->updated_at= Carbon::now(config('app.timezone'));
            $vote->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'vote' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function clear_related_tag($vote_id, $tag_id)
    // VOTE'S RELATED TAGS BLOCK END


    // VOTE'S META KEYWORD BLOCK BEGIN
    public function get_vote_meta_keywords(int $vote_id)
    {
        $vote                         = Vote::find($vote_id);
        $metaKeywords                 = !empty($vote->meta_keywords) ? $vote->meta_keywords : [];
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['vote_id']   = $vote_id;
        $viewParamsArray['vote']      = $vote;
        $viewParamsArray['metaKeywords']  = $metaKeywords;
        $html                         = view($this->getBackendTemplateName() . '.admin.vote.meta_keywords', $viewParamsArray)->render();
        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function META KEYWORD(Request $request)

    public function attach_meta_keyword($vote_id, $meta_keyword)
    {
        $vote = Vote::find($vote_id);
        if ($vote === null) {
            return response()->json(['error_code' => 11, 'message' => 'Vote # "' . $vote_id . '" not found!'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        DB::beginTransaction();
        try {
            $meta_keywords_array= !empty($vote->meta_keywords) ? $vote->meta_keywords : [];
            if ( in_array($meta_keyword, $meta_keywords_array) ) {
                return response()->json(['error_code' => 11, 'message' => 'This vote already has "' . $meta_keyword . '" !'],
                    HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }
            $meta_keywords_array[]= $meta_keyword;
            $vote->meta_keywords= $meta_keywords_array;
            $vote->updated_at= Carbon::now(config('app.timezone'));
            $vote->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'vote' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function attach_meta_keyword($vote_id, $tag_id)

    public function clear_meta_keyword($vote_id, $meta_keyword)
    {
        $vote = Vote::find($vote_id);
        if ($vote === null) {
            return response()->json(['error_code' => 11, 'message' => 'Vote # "' . $vote_id . '" not found!' ],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {

            $meta_keywords_array= $vote->meta_keywords;
            foreach( $meta_keywords_array as $next_key=>$next_value ) {
                if ( $next_value == $meta_keyword ) {
                    unset($meta_keywords_array[$next_key]);
                }
            }
            $vote->meta_keywords= $meta_keywords_array;
            $vote->updated_at= Carbon::now(config('app.timezone'));
            $vote->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json( ['error_code' => 1, 'message' => $e->getMessage() ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function clear_meta_keyword($vote_id, $meta_keyword)  */


    public function update_vote_meta_description($vote_id, $meta_description)
    {
        $vote = Vote::find($vote_id);
        if ($vote === null) {
            return response()->json(['error_code' => 11, 'message' => 'Vote # "' . $vote_id . '" not found!' ],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {

            if( trim($meta_description) == '-' ) $meta_description= '';
            $vote->meta_description= $meta_description;
            $vote->updated_at= Carbon::now(config('app.timezone'));
            $vote->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json( ['error_code' => 1, 'message' => $e->getMessage() ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function update_vote_meta_description($vote_id, $meta_description)  */



    // VOTE'S META KEYWORD BLOCK END

}
