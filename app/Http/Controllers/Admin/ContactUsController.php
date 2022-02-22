<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MyAppController;
use App\ContactUs;
use DB;
use Auth;
use Session;
use Input;

use App\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Traits\funcsTrait;

class ContactUsController extends MyAppController
{
    use funcsTrait;

    public function index($filter_type = '', $filter_value = '')
    {
        $viewParamsArray                              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'],
            ['filter_type' => $filter_type, 'filter_value' => $filter_value]);
        $viewParamsArray['contactUsStatusValueArray'] = $this->SetArrayHeader(['' => ' -Select Accepted- '], ContactUs::getContactUsAcceptedValueArray(false));
        $viewParamsArray['appParamsForJSArray']       = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.contact_us.index', $viewParamsArray);
    }

    public function get_contact_us_dt_listing()
    {
        $request         = request();
        $filter_name     = $request->input('filter_name');
        $filter_accepted = $request->input('filter_accepted');

            $this->debToFile(print_r( $filter_accepted,true),'  get_contact_us_dt_listing  -1 $filter_accepted::');
        $contactUsCollection = ContactUs
            ::getByName($filter_name, true)
            ->getByAccepted($filter_accepted, true)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach( $contactUsCollection as $next_key=> $nextContactUs ) {
            $contactUsCollection[$next_key]->slashed_author_name= addslashes($nextContactUs->author_name);
        }

        return Datatables
            ::of($contactUsCollection)
            ->editColumn('accepted', function ($contactUs) {
                if ( ! isset($contactUs->accepted)) {
                    return '';
                }
                return ContactUs::getContactUsAcceptedLabel($contactUs->accepted);
            })
            ->setRowClass(function ($contactUs) {
                return ! $contactUs->accepted ? 'row_new_status' : '';
            })


            ->editColumn('acceptor_id', function ($contactUs) {
                if ( ! isset($contactUs->acceptor_id)) {
                    return '';
                }
                $user = User::find($contactUs->acceptor_id);
                return ( ! empty($user->name)) ? $user->name : '';
            })

            ->editColumn('message', function ($contactUs) {
                if ( ! isset($contactUs->message)) {
                    return '';
                }
                return $this->concatStr($contactUs->message);
            })

            ->editColumn('accepted_at', function ($contactUs) {
                if (empty($contactUs->accepted_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($contactUs->accepted_at);
            })
            ->editColumn('created_at', function ($contactUs) {
                if (empty($contactUs->created_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($contactUs->created_at);
            })
            ->editColumn('action', '<a href="/admin/contact-us/{{$id}}/edit"><i class=" fa fa-edit"></i></a>')
            ->editColumn('action_delete',
                '<a href="#" onclick="javascript:backendContactUs.deleteContactUs({{$id}},\'{{$slashed_author_name}}\')"><i class="fa fa-remove a_link"></i></a>')
            ->rawColumns(['action', 'action_delete'])
            ->make(true);
    }

    public function edit($contact_us_id)
    {
        $contactUsStatusValueArray = $this->SetArrayHeader(['' => ' -Select Accepted- '], ContactUs::getContactUsAcceptedValueArray(false));
        $viewParamsArray           = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        $contactUs = ContactUs::find($contact_us_id);
        if ($contactUs === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg',
                array_merge($viewParamsArray, [
                    'text'   => 'Contact Us with id # "' . $contact_us_id . '" not found !',
                    'type'   =>
                        'danger',
                    'action' => ''
                ]));
        }

        $viewParamsArray['contactUs']                 = $contactUs;
        $appParamsForJSArray['id']                    = $contact_us_id;
        $viewParamsArray['contactUsStatusValueArray'] = $contactUsStatusValueArray;
        $viewParamsArray['appParamsForJSArray']       = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.contact_us.edit', $viewParamsArray);
    }


    /* delete item with related contact uss */
    public function destroy(Request $request)
    {
        $id = $request->get('id');

        $contactUs = ContactUs::find($id);

        if ($contactUs === null) {
            return response()->json(['error_code' => 11, 'message' => 'Contact us # "' . $id . '" not found!'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $contactUs->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function destroy(Request $request)


    public function contact_us_accept(int $contact_us_id)
    {
        $userProfile = Auth::user();
        $contactUs   = ContactUs::find($contact_us_id);
        if ($contactUs === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', [
                'text'   => 'Contact us with id # "' . $contact_us_id . '" not found !',
                'type'   =>
                    'danger',
                'action' => ''
            ]);
        }

        DB::beginTransaction();
        try {
            $contactUs->accepted    = true;
            $contactUs->accepted_at = now();
            $contactUs->acceptor_id = $userProfile->id;
            $contactUs->save();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'contact_us_id' => $contact_us_id], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => '', 'error_code' => 0], HTTP_RESPONSE_OK);
    } // public function contact_us_accept()


}