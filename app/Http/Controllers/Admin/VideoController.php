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

use App\Http\Controllers\MyAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use App\Http\Traits\funcsTrait;

use App\Http\Requests\VideoRequest;

class VideoController extends MyAppController
{ // https://www.sitepoint.com/displaying-youtube-videos-php/
    use funcsTrait;

    // VIDEOS LISTING/EDITOR BLOCK BEGIN

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
        return view($this->getBackendTemplateName() . '.admin.video.index', $viewParamsArray);
    }

    public function get_videos_dt_listing()
    {
        $request     = request();
        $filter_name = $request->input('filter_name');

        $videosCollection = Video::containingName($filter_name)->get();

        foreach( $videosCollection as $next_key=> $nextVideo ) {
            $videosCollection[$next_key]->slashed_name= addslashes($nextVideo->name);
        }

        return Datatables
            ::of($videosCollection)
            ->editColumn('name', function ($video) {
                return isset($video->name) ? $this->getSpatieVideoLocaledValue($video->name) : '';
            })
            ->editColumn('slug', function ($video) {
                return isset($video->slug) ? $this->getSpatieVideoLocaledValue($video->slug) : '';
            })
            ->editColumn('created_at', function ($video) {
                if (empty($video->created_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($video->created_at);
            })
            ->editColumn('updated_at', function ($video) {
                if (empty($video->updated_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($video->updated_at);
            })
            ->editColumn('action', '<a href="/admin/video/edit/{{$id}}"><i class=" fa fa-edit"></i></a>')
            ->editColumn('action_delete',
                '<a href="#" onclick="javascript:backendVideo.deleteVideo({{$id}},\'{{$slashed_name}}\')"><i class="fa fa-remove a_link"></i></a>')
            ->rawColumns(['action', 'action_delete'])
            ->make(true);
    }

    public function create()            //   http://local-votes.com/admin/video/create
    {
        $viewParamsArray                        = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);
        return view($this->getBackendTemplateName() . '.admin.video.create', $viewParamsArray);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(VideoRequest $request)
    {
        $requestData = $request->all();
        $videoUploadFile = $request->file('video');
        echo '<pre>$requestData::'.print_r($requestData,true).'</pre>';
        echo '<pre>$videoUploadFile::'.print_r($videoUploadFile,true).'</pre>';

//        die("-1 XXZ==");
        $fullPathToVideo = $videoUploadFile->getPathName();
            echo '<pre>$fullPathToVideo::'.print_r($fullPathToVideo,true).'</pre>';
        $video = \Youtube::upload($fullPathToVideo, [
            'title'       => 'My Awesome Video',
            'description' => 'You can also specify your video description here.',
            'videos'	      => ['foo', 'bar', 'baz'],
            'category_id' => 10
        ]);

        $uploaded_video_id= $video->getVideoId();
        echo '<pre>$uploaded_video_id::'.print_r($uploaded_video_id,true).'</pre>';
        
        die("-1 XXZ");
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

        $video_detail_image       = '';
        $video_file_path          = '';
        $video_detail_description = $requestData['description'];
        if ( ! empty($videoUploadFile)) {
            $video_detail_image = VideoDetail::checkValidImgName($videoUploadFile->getClientOriginalName(), with(new VideoDetail)->getImgFilenameMaxLength(), true);
            $video_file_path    = $videoUploadFile->getPathName();
        }

        DB::beginTransaction();
        try {
            $newVideo = SpatieVideo::findOrCreate($requestData['name'], with(new MyVideo)->getVotesVideoType());

            if ( ! empty($video_detail_image) or ! empty($video_detail_description)) {
                $videoDetail              = new VideoDetail();
                $videoDetail->video_id      = $newVideo->id;
                if ( !empty($video_file_path) ) {
                    $videoDetail->image = $video_detail_image;
                }
                $videoDetail->description = $video_detail_description;
                $videoDetail->save();
                if ( !empty($video_file_path) ) {
                    $dest_image = 'public/' . VideoDetail::getVideoDetailImagePath($videoDetail->video_id, $video_detail_image);
                    Storage::disk('local')->put($dest_image, File::get($video_file_path));
                    ImageOptimizer::optimize( storage_path().'/app/'.$dest_image, null );
                }
            } // if ( !empty($video_detail_image) ) {

            if ( ! empty($requestData['order_column'])) {
                $newVideo->order_column = $requestData['order_column'];
                $newVideo->save();
            }
            DB::commit();
//            die("-1 XXZ===");
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput(['name' => $request->get('name'), 'status' => $request->get('status')]);
        }
        $this->setFlashMessage('Video created successfully !', 'success', 'Backend');

//        return redirect()->route('admin.videos.index');
        return redirect('admin/video/edit/' . $newVideo->id);//->route('admin.videos.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Video $video
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($video_id)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $video = MyVideo::find($video_id);
        if ($video == null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', ['text' => 'Video with id # "' . $video_id . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }
        $video->name       = $this->getSpatieVideoLocaledValue($video->name);
        $video->slug       = $this->getSpatieVideoLocaledValue($video->slug);

        $videoDetail = VideoDetail::getByVideoId($video->id)->first();
        if ( ! empty($videoDetail)) {
            $videoDetailImageProps = VideoDetail::setVideoDetailImageProps($videoDetail->video_id, $videoDetail->image, true);
            if (count($videoDetailImageProps) > 0) {
                $videoDetail->setVideoDetailImagePropsAttribute($videoDetailImageProps);
            }
        }
        $viewParamsArray['video']                 = $video;
        $viewParamsArray['videoDetail']           = $videoDetail;
        $appParamsForJSArray['id']              = $video_id;
        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.video.edit', $viewParamsArray);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Video $video
     *
     * @return \Illuminate\Http\Response
     */
    public function update(VideoRequest $request, int $video_id)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $requestData = $request->all();

        $video = MyVideo::find($video_id+999);
        if ($video == null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', ['text' => 'Video with id # "' . $video_id . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }
        $videoDetail = VideoDetail::getByVideoId($video->id)->first();

        $videoDetailUploadFile   = $request->file('image');
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

        $video->updated_at = Carbon::now(config('app.timezone'));

        $video_detail_image= '';
        $video_detail_description = $requestData['description'];
        if ( ! empty($videoDetailUploadFile)) {
            $video_detail_image     = VideoDetail::checkValidImgName($videoDetailUploadFile->getClientOriginalName(), with(new VideoDetail)->getImgFilenameMaxLength(), true);
            $video_file_path = $videoDetailUploadFile->getPathName();
            $video->image    = $video_detail_image;
            $dest_image = 'public/' . VideoDetail::getVideoDetailImagePath($video_id, $video_detail_image);
            Storage::disk('local')->put($dest_image, File::get($video_file_path));
            ImageOptimizer::optimize( storage_path().'/app/'.$dest_image, null );
        } //             if ( !empty($video_detail_image) ) {

        DB::beginTransaction();
        try {
            if ( empty($videoDetail)) {
                $videoDetail              = new VideoDetail();
                $videoDetail->video_id      = $video->id;
            }
            if (!empty($video_detail_image)) {
                $videoDetail->image = $video_detail_image;
            }
            $videoDetail->description     = $video_detail_description;
            $videoDetail->save();

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
        $this->setFlashMessage('Video updated successfully !', 'success', 'Backend');

        return redirect()->route('admin.videos.index');
    }


    /* delete item with related video */
    public function destroy(Request $request)
    {
        $id  = $request->get('id');
        $video = MyVideo::find($id);

        if ($video == null) {
            return response()->json(['error_code' => 11, 'message' => 'Video # "' . $id . '" not found!'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $video->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function destroy(Request $request)


    public function get_video_details_info(int $video_id)
    {
        $video                 = MyVideo::find($video_id);
        $tempVideoRelatedVotes = MyVideogable
            ::getByVideoId($video->id)
            ->getByVideogableType(\App\Vote::class)
            ->get();

        foreach ($tempVideoRelatedVotes as $next_key => $nextTempVideoRelatedVote) {
            $nextVote = Vote
                ::where(with(new Vote)->getTable() . '.id', $nextTempVideoRelatedVote->videogable_id)
                ->leftJoin(\DB::raw('vote_categories'), \DB::raw('vote_categories.id'), '=', \DB::raw('votes.vote_category_id'))
                ->select(\DB::raw(" votes.*, votes.image as vote_image, vote_categories.name as vote_category_name, vote_categories.slug as vote_category_slug"))
                ->first();
            if (empty($nextVote)) {
                continue;
            }

            $voteImageProps = Vote::setVoteImageProps($nextTempVideoRelatedVote->videogable_id, $nextVote->image, false);
            if (count($voteImageProps) > 0) {
                $nextVote->setVoteImagePropsAttribute($voteImageProps);
            }

            $videoRelatedVotes[] = $nextVote;
        }
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['video_id']    = $video_id;
        $viewParamsArray['video']       = $video;
        $videoDetail                    = VideoDetail::getByVideoId($video->id)->first();
        if ( ! empty($videoDetail)) {
            $videoDetailImageProps      = VideoDetail::setVideoDetailImageProps($videoDetail->video_id, $videoDetail->image, true);
            if (count($videoDetailImageProps) > 0) {
                $videoDetail->setVideoDetailImagePropsAttribute($videoDetailImageProps);
            }
        }

        $viewParamsArray['videoDetail'] = $videoDetail;

        $viewParamsArray['videoRelatedVotes'] = $videoRelatedVotes;
        $html                               = view($this->getBackendTemplateName() . '.admin.video.video_details_info', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_video_details_info(Request $request)

    // VIDEO LISTING/EDITOR BLOCK END


}
