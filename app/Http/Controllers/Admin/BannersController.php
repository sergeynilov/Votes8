<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use Session;
use ImageOptimizer;

use Illuminate\Support\Facades\Validator;
use App\Banner;
use App\Http\Controllers\MyAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use App\Http\Traits\funcsTrait;
use App\Http\Requests\BannerRequest;

class BannersController extends MyAppController
{
    use funcsTrait;
    private $banners_tb;
    public function __construct()
    {
        $this->banners_tb= with(new Banner)->getTable();
    }

    // BANNER LISTING/EDITOR BLOCK BEGIN

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter_type = '', $filter_value = '')
    {
        $viewParamsArray                                    = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'],
            ['filter_type' => $filter_type, 'filter_value' => $filter_value]);
        $viewParamsArray['bannerActiveValueArray']          = $this->SetArrayHeader(['' => ' -Select Is Featured- '], Banner::getBannerActiveValueArray(false));
        $viewParamsArray['bannerViewTypeValueArray']        = $this->SetArrayHeader(['' => ' -Select View Type- '], Banner::getBannerViewTypeValueArray(false));
        $viewParamsArray['filter_type']                     = $filter_type;
        $viewParamsArray['filter_value']                    = $filter_value;
        $viewParamsArray['appParamsForJSArray']             = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.banner.index', $viewParamsArray);
    }

    public function get_banner_dt_listing()
    {
        $request = request();

        $filter_text                    = $request->input('filter_text', '');
        $filter_active                  = $request->input('filter_active', '');
        $filter_view_type               = $request->input('filter_view_type', '');

        $bannersCollection = Banner
            ::getByText($filter_text, true)
            ->getByActive($filter_active, true)
            ->getByViewType($filter_view_type)
            ->get();

        foreach( $bannersCollection as $next_key=> $nextBanner ) {
            $bannersCollection[$next_key]->slashed_text= addslashes($nextBanner->text);
        }
        return Datatables
            ::of($bannersCollection)
            ->editColumn('view_type', function ($banner) {
                if ( ! isset($banner->view_type)) {
                    return '::' . $banner->view_type;
                }

                return Banner::getBannerViewTypeLabel($banner->view_type);
            })
            ->setRowClass(function ($banner) {
                return (! $banner->active ? 'row_inactive_status' : '');
            })
            ->editColumn('active', function ($banner) {
                if ( ! isset($banner->active)) {
                    return '::' . $banner->active;
                }

                return Banner::getBannerActiveLabel($banner->active);
            })
            ->editColumn('created_at', function ($banner) {
                if (empty($banner->created_at)) {
                    return '';
                }

                return $this->getCFFormattedDateTime($banner->created_at);
            })
            ->editColumn('action', '<a href="/admin/banners/{{$id}}/edit"><i class=" fa fa-edit"></i></a>')

            ->editColumn('action_delete',
                '<a href="#" onclick="javascript:backendBanner.deleteBanner({{$id}},\'{{$slashed_text}}\')"><i class="fa fa-remove a_link"></i></a>')
            ->rawColumns(['action', 'action_delete'])
            ->make(true);
    }

    public function create()
    {

        $viewParamsArray                                    = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['banner']                          = new Banner();
//        $viewParamsArray['banner']->url                     = '222';
//        $viewParamsArray['banner']->short_descr             = 'short_descr text';
//        $viewParamsArray['banner']->view_type               = 3;
//        $viewParamsArray['banner']->ordering                = 4;
        $viewParamsArray['bannerActiveValueArray']          = $this->SetArrayHeader(['' => ' -Select Active- '], Banner::getBannerActiveValueArray(false));
        $viewParamsArray['bannerViewTypeValueArray']        = $this->SetArrayHeader(['' => ' -Select View Type- '], Banner::getBannerViewTypeValueArray(false));
        $viewParamsArray['appParamsForJSArray']             = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.banner.create', $viewParamsArray);
    }

    public function store(BannerRequest $request)
    {

        $newBanner = new Banner();

        $bannerUploadFile = $request->file('logo');

        $uploaded_file_max_mib = (int)\Config::get('app.uploaded_file_max_mib', 1);
        $max_size              = 1024 * $uploaded_file_max_mib;
        $rules                 = array(
            'logo' => 'max:' . $max_size,
        );
        $validator             = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator);
        }

        $newBanner->text            = $request->get('text');
        $newBanner->short_descr     = $request->get('short_descr');
        $newBanner->url             = $request->get('url');
        $newBanner->active          = $request->get('active');
        $newBanner->view_type       = $request->get('view_type');
        $newBanner->ordering        = $request->get('ordering');

        if ( ! empty($bannerUploadFile)) {
            $banner_logo     = Banner::checkValidImgName($bannerUploadFile->getClientOriginalName(), with(new Banner)->getImgFilenameMaxLength(), true);
            $banner_file_path = $bannerUploadFile->getPathName();
            $newBanner->logo    = $banner_logo;
        }

        DB::beginTransaction();
        try {
            $newBanner->save();//
            if ( ! empty($banner_logo)) {
                $dest_logo = 'public/' . Banner::getBannerLogoPath($newBanner->id, $banner_logo);
                Storage::disk('local')->put($dest_logo, File::get($banner_file_path));
                ImageOptimizer::optimize( storage_path().'/app/'.$dest_logo, null );
            } // if ( !empty($banner_logo) ) {

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput( [ 'text' => $request->get('text') ] );
        }
        $this->setFlashMessage('Banner created successfully !', 'success', 'Backend');

        return redirect('admin/banners/' . $newBanner->id . '/edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banner $banner
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($banner_id)
    {
        $banner = Banner::find($banner_id);
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        if ($banner === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', [
                'text'   => 'Banner with id # "' . $banner_id . '" not found !',
                'type'   => 'danger',
                'action' => ''
            ],
                $viewParamsArray);
        }
        $bannerImageProps = Banner::setBannerLogoProps($banner->id, $banner->logo, true);
        if (count($bannerImageProps) > 0) {
            $banner->setBannerLogoPropsAttribute($bannerImageProps);
        }

        $viewParamsArray['banner']                     = $banner;
        $appParamsForJSArray['id']                          = $banner_id;
        $viewParamsArray['bannerActiveValueArray']          = $this->SetArrayHeader(['' => ' -Select Active- '], Banner::getBannerActiveValueArray(false));
        $viewParamsArray['bannerViewTypeValueArray']        = $this->SetArrayHeader(['' => ' -Select View Type- '], Banner::getBannerViewTypeValueArray(false));

        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.banner.edit', $viewParamsArray);
    }

    public function update(BannerRequest $request, int $banner_id)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        $banner = Banner::find($banner_id);
        if ($banner === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', [
                'text'   => 'Banner with id # "' . $banner_id . '" not found !',
                'type'   => 'danger',
                'action' => ''
            ],
                $viewParamsArray);
        }
        $bannerUploadFile = $request->file('logo');

        $uploaded_file_max_mib = (int)\Config::get('app.uploaded_file_max_mib', 1);
        $max_size              = 1024 * $uploaded_file_max_mib;
        $rules                 = array(
            'logo' => 'max:' . $max_size,
        );
        $validator             = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        $banner->text            = $request->get('text');
        $banner->short_descr     = $request->get('short_descr');
        $banner->url             = $request->get('url');
        $banner->active          = $request->get('active');
        $banner->view_type       = $request->get('view_type');
        $banner->ordering        = $request->get('ordering');

        if ( ! empty($bannerUploadFile)) {
            $banner_logo     = Banner::checkValidImgName($bannerUploadFile->getClientOriginalName(), with(new Banner)->getImgFilenameMaxLength(), true);
            $banner_file_path = $bannerUploadFile->getPathName();
            $banner->logo     = $banner_logo;
        } // if (!empty($bannerUploadFile)) {

        if ( ! empty($banner_logo)) {
            $dest_logo = 'public/' .  Banner::getBannerLogoPath($banner_id, $banner_logo);
            Storage::disk('local')->put($dest_logo, File::get($banner_file_path));
            ImageOptimizer::optimize( storage_path().'/app/'.$dest_logo, null );
        } //             if ( !empty($banner_logo) ) {

        DB::beginTransaction();
        try {
            $banner->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }
        $this->setFlashMessage('Banner updated successfully !', 'success', 'Backend');

        //resources/views/defaultBS41Backend/admin/banner/index.blade.php
        return redirect()->route('admin.banners.index');
    }


    /* delete banner with related */
    public function destroy(Request $request)
    {
        $id          = $request->get('id');
        $banner      = Banner::find($id);

        if ($banner === null) {
            return response()->json(['error_code' => 11, 'message' => 'Banner # "' . $id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $banner->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function destroy(Request $request)


    // BANNER LISTING/EDITOR BLOCK END

}
