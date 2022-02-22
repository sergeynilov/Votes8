<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MyAppController;
use DB;
use Carbon\Carbon;

use App\ExternalNewsImporting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use App\Http\Traits\funcsTrait;
use App\Http\Requests\ExternalNewsImportingRequest;

class ExternalNewsImportingController extends MyAppController
{
    use funcsTrait;

    public function index($filter_type = '', $filter_value = '')
    {
        $viewParamsArray                                     = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'],
            ['filter_type' => $filter_type, 'filter_value' => $filter_value]);
        $viewParamsArray['externalNewsImportingStatusValueArray'] = $this->SetArrayHeader(['' => ' -Select Status- '],
            ExternalNewsImporting::getExternalNewsImportingStatusValueArray(false));
        $viewParamsArray['appParamsForJSArray']              = json_encode($appParamsForJSArray);
        return view($this->getBackendTemplateName() . '.admin.external_news_importing.index', $viewParamsArray);
    }

    public function get_external_news_importing_dt_listing()
    {
        $request = request();
        $filter_title  = $request->input('filter_title', '');
        $filter_status = $request->input('filter_status', '');
        $externalNewsImportingCollection = ExternalNewsImporting
            ::getByTitle($filter_title, true)
            ->getByStatus($filter_status, true)
            ->get();

        foreach( $externalNewsImportingCollection as $next_key=> $nextExternalNewsImportingCollection ) {
            $externalNewsImportingCollection[$next_key]->slashed_title= addslashes($nextExternalNewsImportingCollection->title);
        }

        return Datatables
            ::of($externalNewsImportingCollection)
            ->editColumn('status', function ($externalNewsImporting) {
                if ( ! isset($externalNewsImporting->status)) {
                    return '';
                }
                return ExternalNewsImporting::getExternalNewsImportingStatusLabel($externalNewsImporting->status);
            })
            ->setRowClass(function ($externalNewsImporting) {
                return ! $externalNewsImporting->status ? 'row_instatus_status' : '';
            })
            ->editColumn('created_at', function ($externalNewsImporting) {
                if (empty($externalNewsImporting->created_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($externalNewsImporting->created_at);
            })
            ->editColumn('action', '<a href="/admin/external-news-importing/{{$id}}/edit"><i class=" fa fa-edit"></i></a>')
            ->editColumn('action_delete',
                '<a href="#" onclick="javascript:backendExternalNewsImporting.deleteExternalNewsImporting({{$id}},\'{{$slashed_title}}\')"><i class="fa fa-remove a_link"></i></a>')
            ->rawColumns(['action', 'action_delete'])
            ->make(true);
    }

    public function create()
    {
        $viewParamsArray                      = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['externalNewsImporting']                 = new ExternalNewsImporting();
        $viewParamsArray['externalNewsImporting']->import_image   = '0';
        $viewParamsArray['externalNewsImporting']->status         = '0';

        $viewParamsArray['externalNewsImportingStatusValueArray'] = $this->SetArrayHeader(['' => ' -Select Status- '], ExternalNewsImporting::getExternalNewsImportingStatusValueArray(false));
        $viewParamsArray['externalNewsImportingImportImageValueArray'] = $this->SetArrayHeader(['' => ' -Select Import Image- '],
            ExternalNewsImporting::getExternalNewsImportingImportImageValueArray(false));
        $viewParamsArray['appParamsForJSArray']    = json_encode($appParamsForJSArray);
        return view($this->getBackendTemplateName() . '.admin.external_news_importing.create', $viewParamsArray);
    }

    public function store(ExternalNewsImportingRequest $request)
    {
        $externalNewsImporting                   = new ExternalNewsImporting();
        $externalNewsImporting->title            = $request->get('title');
        $externalNewsImporting->status           = $request->get('status');
        $externalNewsImporting->url              = $request->get('url');
        DB::beginTransaction();
        try {
            $externalNewsImporting->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput(['title' => $request->get('title'), 'status' => $request->get('status'), 'url' => $request->get('url')]);
        }
        $this->setFlashMessage('External news importing created successfully !', 'success', 'Backend');

        return redirect()->route('admin.external-news-importing.index');
    }

    public function edit($external_news_importing_id)
    {
        $externalNewsImportingStatusValueArray = $this->SetArrayHeader(['' => ' -Select Status- '], ExternalNewsImporting::getExternalNewsImportingStatusValueArray(false));
        $externalNewsImporting                 = ExternalNewsImporting::find($external_news_importing_id);
        $viewParamsArray                       = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        if ($externalNewsImporting === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', [
                'text'   => 'External news importing with id # "' . $external_news_importing_id . '" not found !',
                'type'   =>
                    'danger',
                'action' => ''
            ], $viewParamsArray);
        }

        $viewParamsArray['externalNewsImporting']                 = $externalNewsImporting;
        $appParamsForJSArray['id']                                = $external_news_importing_id;
        $viewParamsArray['externalNewsImportingStatusValueArray'] = $externalNewsImportingStatusValueArray;
        $viewParamsArray['externalNewsImportingImportImageValueArray'] = $this->SetArrayHeader(['' => ' -Select Import Image- '],
            ExternalNewsImporting::getExternalNewsImportingImportImageValueArray(false));

        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.external_news_importing.edit', $viewParamsArray);
    }

    public function update(ExternalNewsImportingRequest $request, int $external_news_importing_id)
    {
        $viewParamsArray  = $this->getAppParameters(true, ['csrf_token']);
        $externalNewsImporting = ExternalNewsImporting::find($external_news_importing_id);
        if ($externalNewsImporting === null) {
            $viewParamsArray['text'] = 'External news importing with id # "' . $external_news_importing_id . '" not found !';
            $viewParamsArray['type'] = 'danger';
            return View( $this->getBackendTemplateName() . '.admin.dashboard.msg', $viewParamsArray );
        }
        $externalNewsImporting->title            = $request->get('title');
        $externalNewsImporting->status           = $request->get('status');
        $externalNewsImporting->url              = $request->get('url');
        $externalNewsImporting->updated_at       = Carbon::now(config('app.timezone'));

        DB::beginTransaction();
        try {
            $externalNewsImporting->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }
        $this->setFlashMessage('External news importing updated successfully !', 'success', 'Backend');

        return redirect()->route('admin.external-news-importing.index');
    }


    /* delete item with related external news importing */
    public function destroy(Request $request)
    {
        $id = $request->get('id');
        $externalNewsImporting = ExternalNewsImporting::find($id);

        if ($externalNewsImporting === null) {
            return response()->json(['error_code' => 11, 'message' => 'External news importing # "' . $id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $externalNewsImporting->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function destroy(Request $request)

}