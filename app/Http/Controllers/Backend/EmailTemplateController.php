<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Traits\ImageUpload;
use DataTables;
use Illuminate\Http\Request;
use Validator;

class EmailTemplateController extends Controller
{
    use ImageUpload;

    function __construct()
    {
        $this->middleware('permission:email-template');
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = EmailTemplate::query()->latest();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', 'backend.email.include.__name')
                ->addColumn('status', 'backend.email.include.__status')
                ->addColumn('action', 'backend.email.include.__action')
                ->rawColumns(['name', 'status', 'action'])
                ->make(true);
        }
        return view('backend.email.template');
    }

    public function edit($id)
    {
        $template = EmailTemplate::find($id);
        return view('backend.email.edit', compact('template'));
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'message_body' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();
        $data = [
            'subject' => $input['subject'],
            'message_body' => nl2br($input['message_body']),
            'title' => $input['title'],
            'salutation' => $input['salutation'],
            'button_level' => $input['button_level'],
            'button_link' => $input['button_link'],
            'footer_status' => $input['footer_status'],
            'footer_body' => nl2br($input['footer_body']),
            'bottom_status' => $input['bottom_status'],
            'bottom_title' => $input['bottom_title'],
            'bottom_body' => nl2br($input['bottom_body']),
            'status' => $input['status'],
        ];

        $template = EmailTemplate::find($input['id']);
        if (isset($input['banner']) && is_file($input['banner'])) {
            $data['banner'] = self::imageUploadTrait($input['banner'], $template->banner);
        }

        $template->update($data);

        notify()->success(__('Email Template Updated Successfully'));
        return redirect()->route('admin.email-template');
    }
}
