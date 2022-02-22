<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use Session;
use ImageOptimizer;

use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\User;
use App\PageContent;
use App\PageContentImage;
use App\Http\Controllers\MyAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use App\Http\Traits\funcsTrait;
use App\Http\Requests\PageContentRequest;

class PageContentsController extends MyAppController
{
    use funcsTrait;
    private $users_tb;
    private $page_contents_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->page_contents_tb= with(new PageContent)->getTable();
    }

    // PAGE_CONTENT LISTING/EDITOR BLOCK BEGIN

    public function index($filter_type = '', $filter_value = '')
    {
        $viewParamsArray                                    = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'],
            ['filter_type' => $filter_type, 'filter_value' => $filter_value]);
        $viewParamsArray['pageContentIsFeaturedValueArray'] = $this->SetArrayHeader(['' => ' -Select Is Featured- '], PageContent::getPageContentIsFeaturedValueArray(false));
        $viewParamsArray['pageContentPageTypeValueArray']   = $this->SetArrayHeader(['' => ' -Select Page Type- '], PageContent::getPageContentPageTypeValueArray(false));
        $viewParamsArray['pageContentIsHomepageValueArray'] = $this->SetArrayHeader(['' => ' -Select Is Homepage- '], PageContent::getPageContentIsHomepageValueArray(false));
        $viewParamsArray['filter_type']                     = $filter_type;
        $viewParamsArray['filter_value']                    = $filter_value;
//        echo '<pre>$viewParamsArray::'.print_r($viewParamsArray,true).'</pre>';
        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.page_content.index', $viewParamsArray);
    }

    public function get_page_content_dt_listing()
    {
        $request = request();

        $filter_title                    = $request->input('filter_title', '');
        $filter_published                = $request->input('filter_published', '');
        $filter_is_featured              = $request->input('filter_is_featured', '');
        $filter_page_type                = $request->input('filter_page_type', '');
        $filter_is_homepage              = $request->input('filter_is_homepage', '');

        $pageContentsCollection = PageContent
            ::getByTitle($filter_title, true)
            ->getByPublished($filter_published, true)
            ->getByIsFeatured($filter_is_featured, true)
            ->getByIsHomepage($filter_is_homepage)
            ->getByPageType($filter_page_type)
            ->leftJoin($this->users_tb, $this->users_tb.'.id', '=', $this->page_contents_tb.'.creator_id')
            ->select( $this->page_contents_tb.'.*', $this->users_tb.'.username as creator_username' )
            ->get();

        foreach( $pageContentsCollection as $next_key=> $nextPageContent ) {
            $pageContentsCollection[$next_key]->slashed_title= addslashes($nextPageContent->title);
        }
        return Datatables
            ::of($pageContentsCollection)
            ->editColumn('page_type', function ($page_content) {
                if ( ! isset($page_content->page_type)) {
                    return '::' . $page_content->page_type;
                }

                return PageContent::getPageContentPageTypeLabel($page_content->page_type);
            })
            ->setRowClass(function ($page_content) {
                return (! $page_content->published ? 'row_inactive_status' : '');
            })
            ->editColumn('is_featured', function ($page_content) {
                if ( ! isset($page_content->is_featured)) {
                    return '::' . $page_content->is_featured;
                }

                return PageContent::getPageContentIsFeaturedLabel($page_content->is_featured);
            })
            ->editColumn('is_homepage', function ($page_content) {
                if ( ! isset($page_content->is_homepage)) {
                    return '::' . $page_content->is_homepage;
                }

                return PageContent::getPageContentIsHomepageLabel($page_content->is_homepage);
            })
            ->editColumn('published', function ($page_content) {
                if ( ! isset($page_content->published)) {
                    return '::' . $page_content->published;
                }

                return PageContent::getPageContentPublishedLabel($page_content->published);
            })
            ->editColumn('created_at', function ($page_content) {
                if (empty($page_content->created_at)) {
                    return '';
                }

                return $this->getCFFormattedDateTime($page_content->created_at);
            })
            ->editColumn('updated_at', function ($page_content) {
                if (empty($page_content->updated_at)) {
                    return '';
                }

                return $this->getCFFormattedDateTime($page_content->updated_at);
            })
            ->editColumn('action', '<a href="/admin/page-contents/{{$id}}/edit"><i class=" fa fa-edit"></i></a>')

            ->editColumn('action_delete',
                '<a href="#" onclick="javascript:backendPageContent.deletePageContent({{$id}},\'{{$slashed_title}}\')"><i class="fa fa-remove a_link"></i></a>')
            ->rawColumns(['action', 'action_delete'])
            ->make(true);
    }

    public function create()
    {

        $viewParamsArray                                    = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['pageContent']                     = new PageContent();
        $viewParamsArray['pageContent']->is_homepage        = '0';
        $viewParamsArray['pageContent']->page_type          = 'N';
        $viewParamsArray['pageContent']->published          = '1';
        $viewParamsArray['pageContent']->is_featured        = '0';
        $viewParamsArray['pageContentIsFeaturedValueArray'] = $this->SetArrayHeader(['' => ' -Select Is Featured- '], PageContent::getPageContentIsFeaturedValueArray(false));
        $viewParamsArray['pageContentPageTypeValueArray']   = $this->SetArrayHeader(['' => ' -Select Page Type- '], PageContent::getPageContentPageTypeValueArray(false));
        $viewParamsArray['pageContentIsHomepageValueArray'] = $this->SetArrayHeader(['' => ' -Select Is Homepage- '], PageContent::getPageContentIsHomepageValueArray(false));
        $viewParamsArray['pageContentPublishedValueArray']  = $this->SetArrayHeader(['' => ' -Select Is Published- '], PageContent::getPageContentPublishedValueArray(false));
        $viewParamsArray['appParamsForJSArray']             = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.page_content.create', $viewParamsArray);
    }

    public function store(PageContentRequest $request)
    {
        $newPageContent = new PageContent();
        $page_contentUploadFile = $request->file('image');

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

        $loggedUser                    = Auth::user();
        $newPageContent->title           = $request->get('title');
        $newPageContent->content         = $request->get('content');
        $newPageContent->content_shortly = $request->get('content_shortly');
        $newPageContent->creator_id      = $loggedUser->id;
        $newPageContent->is_featured     = $request->get('is_featured');
        $newPageContent->published       = $request->get('published');
        $newPageContent->is_homepage     = $request->get('is_homepage');
        $newPageContent->page_type       = $request->get('page_type');
        $newPageContent->source_type     = $request->get('source_type');
        $newPageContent->source_url      = $request->get('source_url');

        if ( ! empty($page_contentUploadFile)) {
            $page_content_image     = PageContent::checkValidImgName($page_contentUploadFile->getClientOriginalName(), with(new PageContent)->getImgFilenameMaxLength(), true);
            $page_content_file_path = $page_contentUploadFile->getPathName();
            $newPageContent->image    = $page_content_image;
        }

        DB::beginTransaction();
        try {
            $newPageContent->save();

            if ( ! empty($page_content_image)) {
                $dest_image = 'public/' . PageContent::getPageContentImagePath($newPageContent->id, $page_content_image);
                Storage::disk('local')->put($dest_image, File::get($page_content_file_path));
                ImageOptimizer::optimize( storage_path().'/app/'.$dest_image, null );
            } // if ( !empty($page_content_image) ) {

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput(['name' => $request->get('name'), 'status' => $request->get('status')]);
        }
        $this->setFlashMessage('Page created successfully !', 'success', 'Backend');

        return redirect('admin/page-contents/' . $newPageContent->id . '/edit');
    }

    public function edit($page_content_id)
    {
        $pageContent = PageContent::find($page_content_id);
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        if ($pageContent === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', [
                'text'   => 'Page Content with id # "' . $page_content_id . '" not found !',
                'type'   => 'danger',
                'action' => ''
            ],
                $viewParamsArray);
        }
        $pageContentImageProps = PageContent::setPageContentImageProps($pageContent->id, $pageContent->image, true);
        if (count($pageContentImageProps) > 0) {
            $pageContent->setPageContentImagePropsAttribute($pageContentImageProps);
        }

        $viewParamsArray['pageContent']                     = $pageContent;
        $appParamsForJSArray['id']                          = $page_content_id;
        $viewParamsArray['pageContentIsFeaturedValueArray'] = $this->SetArrayHeader(['' => ' -Select Is Featured- '], PageContent::getPageContentIsFeaturedValueArray(false));
        $viewParamsArray['pageContentPageTypeValueArray']   = $this->SetArrayHeader(['' => ' -Select Page Type- '], PageContent::getPageContentPageTypeValueArray(false));
        $viewParamsArray['pageContentIsHomepageValueArray'] = $this->SetArrayHeader(['' => ' -Select Is Homepage- '], PageContent::getPageContentIsHomepageValueArray(false));
        $viewParamsArray['pageContentPublishedValueArray']  = $this->SetArrayHeader(['' => ' -Select Is Published- '], PageContent::getPageContentPublishedValueArray(false));

        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.page_content.edit', $viewParamsArray);
    }

    public function update(PageContentRequest $request, int $page_content_id)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        $pageContent = PageContent::find($page_content_id);
        if ($pageContent === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', [
                'text'   => 'Page Content with id # "' . $page_content_id . '" not found !',
                'type'   => 'danger',
                'action' => ''
            ],
                $viewParamsArray);
        }
        $pageContentUploadFile = $request->file('image');

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

        $loggedUser                   = Auth::user();
        $pageContent->title           = $request->get('title');
        $pageContent->content         = $request->get('content');
        $pageContent->content_shortly = $request->get('content_shortly');
        $pageContent->creator_id      = $loggedUser->id;
        $pageContent->is_featured     = $request->get('is_featured');
        $pageContent->published       = $request->get('published');
        $pageContent->is_homepage     = $request->get('is_homepage');
        $pageContent->page_type       = $request->get('page_type');
        $pageContent->source_type     = $request->get('source_type');
        $pageContent->source_url      = $request->get('source_url');
        $pageContent->updated_at      = Carbon::now(config('app.timezone'));

        if ( ! empty($pageContentUploadFile)) {
            $page_content_image     = PageContent::checkValidImgName($pageContentUploadFile->getClientOriginalName(), with(new PageContent)->getImgFilenameMaxLength(), true);
            $page_content_file_path = $pageContentUploadFile->getPathName();
            $pageContent->image     = $page_content_image;
        } // if (!empty($page_contentUploadFile)) {

        if ( ! empty($page_content_image)) {
            $dest_image = 'public/' .  PageContent::getPageContentImagePath($page_content_id, $page_content_image);
            Storage::disk('local')->put($dest_image, File::get($page_content_file_path));
            ImageOptimizer::optimize( storage_path().'/app/'.$dest_image, null );
        } //             if ( !empty($page_content_image) ) {

        DB::beginTransaction();
        try {
            $pageContent->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }
        $this->setFlashMessage('Page updated successfully !', 'success', 'Backend');

        //resources/views/defaultBS41Backend/admin/page_content/index.blade.php
        return redirect()->route('admin.page-contents.index');
    }


    /* delete page_content with related */
    public function destroy(Request $request)
    {
        $id          = $request->get('id');
        $pageContent = PageContent::find($id);

        if ($pageContent === null) {
            return response()->json(['error_code' => 11, 'message' => 'Page Content # "' . $id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $pageContent->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function destroy(Request $request)

    // PAGE_CONTENT LISTING/EDITOR BLOCK END


    // PAGE_CONTENT IMAGES BLOCK START
    public function get_related_page_content_images(int $page_content_id)
    {
        $page_content_image_preview_width= with (new PageContentImage())->getImgPreviewWidth();

        $pageContentImages = PageContentImage
            ::getByPageContent($page_content_id)
            ->orderBy('is_main', 'desc')
            ->get()
            ->map(function ($nextTempPageContentImage, $key) use ($page_content_image_preview_width) {
                $pageContentImageProps                        = PageContentImage::setPageContentImageImageProps($nextTempPageContentImage->page_content_id, $nextTempPageContentImage->filename,
                    false);
                $pageContentImageProps['image_preview_width'] = $page_content_image_preview_width;
                if ( !empty($pageContentImageProps['file_width']) and $pageContentImageProps['file_width'] < $page_content_image_preview_width) {
                    $pageContentImageProps['image_preview_width'] = $pageContentImageProps['file_width'];
                }
                if (count($pageContentImageProps) > 0) {
                    $pageContentImageProps['page_content_id'] = $nextTempPageContentImage->id;
                    $pageContentImageProps['info']            = $nextTempPageContentImage->info;
                    $pageContentImageProps['created_at']      = $nextTempPageContentImage->created_at;
                    $nextTempPageContentImage->setPageContentImageImagePropsAttribute($pageContentImageProps);
                }
                return $nextTempPageContentImage;
            })
            ->all();

        $viewParamsArray                              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['page_content_id']           = $page_content_id;
        $viewParamsArray['pageContentImages']         = $pageContentImages;
        $viewParamsArray['page_content_images_count'] = count($pageContentImages);
        $html                                         = view($this->getBackendTemplateName() . '.admin.page_content.page_content_images', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_related_page_content_images(int $page_content_id)


    public function upload_page_content_image_to_tmp_page_content()
    {
        $unique_session_id = Session::getId();
        $request           = request();
        $requestData       = $request->all();
//        $this->debToFile(print_r($requestData, true), '  upload_page_content_image_to_tmp_page_content  --0 $requestData::');

        $page_content_id   = $requestData['page_content_id'];
//        $this->debToFile(print_r($page_content_id, true), '  upload_page_content_image_to_tmp_page_content  -20 page_content_id::');
//        $this->debToFile(print_r($_FILES, true), '  upload_page_content_image_to_tmp_page_content  -21 $_FILES::');

        $UPLOADS_TPM_PAGE_CONTENT_IMAGES_DIR = 'tmp/' . with(new PageContentImage)->getTable();

        
        $dst_tmp_directory             = $UPLOADS_TPM_PAGE_CONTENT_IMAGES_DIR . '_' . $unique_session_id;
        $tmp_dest_dirname_url          = '/' . $UPLOADS_TPM_PAGE_CONTENT_IMAGES_DIR . '_' . $unique_session_id;
        $src_filename = $_FILES['files']['tmp_name'][0];
//        $this->debToFile(print_r($src_filename, true), '  $tmpPageContentImageImagesDirs  -24 $src_filename::');

        $img_basename = PageContentImage::checkValidImgName($_FILES['files']['name'][0], 0, true);
//        $this->debToFile(print_r($img_basename, true), '  $tmpPageContentImageImagesDirs  -25 $img_basename::');
//        $this->debToFile(print_r($dst_tmp_directory, true), '  $tmpPageContentImageImagesDirs  -251 v::');

        $img_Extension= strtolower($this->getFilenameExtension($img_basename));
        $info_message= '';
        if ( $img_Extension == 'avi' ) {
            $info_message= 'As not all avi files are supported, you would better to convert it to MP4 format.';
        }
        $tmp_dest_filename = $dst_tmp_directory . DIRECTORY_SEPARATOR . $img_basename;
//        $this->debToFile(print_r($tmp_dest_filename, true), '  $tmpPageContentImageImagesDirs  -26 $tmp_dest_filename::');
        $tmp_dest_filename_path= storage_path('/app/public/').$tmp_dest_filename;

        $dest_filename = 'public/' . $tmp_dest_filename;

//        $this->debToFile(print_r($dest_filename, true), '  upload_image_to_page_content  --2 $dest_filename::');

        Storage::disk('local')->put($dest_filename, File::get($src_filename));
        ImageOptimizer::optimize( storage_path().'/app/'.$dest_filename, null );

        $filesize      = filesize($tmp_dest_filename_path);

        $pageContentImageProps = with(new PageContentImage)->getCFImageProps($tmp_dest_filename_path, []);
//        $this->debToFile(print_r($pageContentImageProps, true), '  $tmpPageContentImageImagesDirs  -28 $pageContentImageProps::');
        $resArray = [
            "files" =>
                [
                    "short_name"   => $img_basename,
                    "name"         => $tmp_dest_filename,
                    "size"         => $filesize,
                    'FilenameInfo' => $this->getImageShowSize( $tmp_dest_filename_path , with(new PageContentImage)->getImgPreviewWidth(),
                        with(new PageContentImage)->getImgPreviewHeight()),
                    'file_info'    => ! empty($pageContentImageProps['file_info']) ? $pageContentImageProps['file_info'] : '',
                    "size_label"   => $this->getNiceFileSize($filesize),
                    "info_message" => $info_message,
                    "url"          => '/storage'.$tmp_dest_dirname_url . '/' . $img_basename . '?tm=' . time(),
                ]
        ];
        echo json_encode($resArray);
    }  // upload_page_content_image_to_tmp_page_content



    public function upload_image_to_page_content()
    {
        $request     = request();
        $requestData = $request->all();

        $page_content_image_tmp_path = $requestData['page_content_image'];
        $page_content_id             = $requestData['page_content_id'];

        $pageContent = PageContent::find($page_content_id);
        if ($pageContent === null) {
            return ['message' => 'Page Content with id # "' . $page_content_id . '" not found !', 'error_code' => 1, 'page_content_id' => $page_content_id];
        }

        $is_main_image               = $requestData['is_main_image'];
        $is_video                    = $requestData['is_video'];
        $video_width                 = $requestData['video_width'];
        $video_height                = $requestData['video_height'];
        $info                        = $requestData['info'];

        $img_basename = PageContentImage::checkValidImgName(basename($page_content_image_tmp_path), 0, true);
        $similarPageContentImage=  PageContentImage::getSimilarPageContentImageByFilename( $img_basename, $page_content_id );
        if ( !empty($similarPageContentImage) ) {
            return response()->json(['error_code' => 11, 'message' => 'Image "'.$img_basename.'" has been already uploaded for this page !'], HTTP_RESPONSE_OK);
        }

        $page_content_image_id = '';
        DB::beginTransaction();
        try {
//            $this->debToFile($img_basename, ' upload_image_to_page_content  $img_basename::');
            if (strtoupper($is_main_image) == 'Y') {
                $pageContentImages = PageContentImage
                    ::getByPageContent($page_content_id)
                    ->get();
                foreach ($pageContentImages as $nexPageContentImage) {
                    $nexPageContentImage->is_main = false;
                    $nexPageContentImage->save();
                }
            }

            $newPageContentImage                  = new PageContentImage();
            $newPageContentImage->page_content_id = $page_content_id;
            $newPageContentImage->filename        = $img_basename;
            $newPageContentImage->is_main         = strtoupper($is_main_image) == 'Y';
            $newPageContentImage->is_video        = strtoupper($is_video) == 'Y';
            $newPageContentImage->video_width     = $video_width;
            $newPageContentImage->video_height    = $video_height;
            $newPageContentImage->info            = $info;
            $newPageContentImage->save();
            $page_content_image_id = $newPageContentImage->id;

            $pageContent->updated_at= Carbon::now(config('app.timezone'));
            $pageContent->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return ['message' => $e->getMessage(), 'error_code' => 1, 'page_content_image_id' => $page_content_image_id];
        }
        $dest_filename = 'public/' . PageContentImage::getPageContentImageImagePath($page_content_id, $img_basename, true);
//        $this->debToFile(print_r($page_content_image_tmp_path, true), '  upload_image_to_page_content  --10 $page_content_image_tmp_path::');
//        $this->debToFile(print_r($dest_filename, true), '  upload_image_to_page_content  --2 $dest_filename::');

        Storage::disk('local')->put($dest_filename, File::get( storage_path('/app/public/').$page_content_image_tmp_path) );
        ImageOptimizer::optimize( storage_path().'/app/'.$dest_filename, null );
        return ['message' => '', 'error_code' => 0, 'page_content_image_id' => $page_content_image_id, 'image' => $img_basename];
    } // public function upload_image_to_page_content()


    public function delete_page_content_image(Request $request)
    {
        $page_content_image_id   = $request->get('page_content_image_id');
//        $this->debToFile(print_r($page_content_image_id, true), '  delete_page_content_image  --0 page_content_image_id::');

        $pageContentImage = PageContentImage::find($page_content_image_id);
        if ($pageContentImage === null) {
            return response()->json(['error_code' => 11, 'message' => 'Page content image # "' . $page_content_image_id . '" not found!'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }


        $pageContent = PageContent::find($pageContentImage->page_content_id);
        if ($pageContent === null) {
            return ['message' => 'Page Content with id # "' . $pageContentImage->page_content_id . '" not found !', 'error_code' => 1, 'page_content_id' => $pageContentImage->page_content_id];
        }

        DB::beginTransaction();
        try {

            $filename_to_delete = 'public/' . PageContent::getPageContentImagePath($pageContentImage->page_content_id, $pageContentImage->filename);
            Storage::delete($filename_to_delete);

            $pageContentImage->delete();
            $pageContent->updated_at= Carbon::now(config('app.timezone'));
            $pageContent->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function delete_page_content_image()

    // PAGE_CONTENT IMAGES BLOCK END

}
