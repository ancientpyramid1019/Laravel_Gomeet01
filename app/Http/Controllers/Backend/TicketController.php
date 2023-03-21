<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Traits\ImageUpload;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{

    use ImageUpload;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:support-ticket-list|support-ticket-action', ['only' => ['index']]);
        $this->middleware('permission:support-ticket-action', ['only' => ['closeNow', 'reply', 'show']]);

    }


    public function index(Request $request, $id = null)
    {
        if ($request->ajax()) {

            if ($id) {
                $data = Ticket::where('user_id', $id)->latest();
            } else {
                $data = Ticket::query()->latest();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', 'backend.ticket.include.__name')
                ->addColumn('status', 'backend.ticket.include.__status')
                ->addColumn('action', 'backend.ticket.include.__action')
                ->rawColumns(['name', 'status', 'action'])
                ->make(true);
        }

        return view('backend.ticket.index');
    }

    public function show($uuid)
    {
        $ticket = Ticket::uuid($uuid);

        return view('backend.ticket.show', compact('ticket'));
    }

    public function closeNow($uuid)
    {
        Ticket::uuid($uuid)->close();
        notify()->success('Ticket Closed successfully', 'success');
        return Redirect::route('admin.ticket.index');

    }

    public function reply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }


        $input = $request->all();

        $adminId = \Auth::id();

        $data = [
            'model' => 'admin',
            'user_id' => $adminId,
            'message' => nl2br($input['message']),
            "attach" => $request->hasFile('attach') ? self::imageUploadTrait($input['attach']) : null,
        ];


        $ticket = Ticket::uuid($input['uuid']);


        $ticket->messages()->create($data);

        notify()->success('Ticket Reply successfully', 'success');

        return Redirect::route('admin.ticket.show', $ticket->uuid);

    }
}
