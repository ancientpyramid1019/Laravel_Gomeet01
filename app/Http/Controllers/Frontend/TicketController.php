<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Traits\ImageUpload;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Redirect;

class TicketController extends Controller
{
    use ImageUpload;

    public function index()
    {

        $tickets = Ticket::where('user_id', Auth::id())->get();
        return view('frontend.ticket.index', compact('tickets'));

    }

    public function new()
    {
        return view('frontend.ticket.new');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        /** @var User $user */

        $user = Auth::user();

        $input = $request->all();


        $data = [
            'uuid' => 'SUPT' . rand(100000, 999999),
            'title' => $input['title'],
            'message' => nl2br($input['message']),
            "attach" => $request->hasFile('attach') ? self::imageUploadTrait($input['attach']) : null,
        ];


        $ticket = $user->tickets()->create($data);


        notify()->success('Your Ticket Was created successfully', 'success');

        return Redirect::route('user.ticket.show', $ticket->uuid);

    }


    public function show($uuid)
    {


        $ticket = Ticket::uuid($uuid)->close();


        if ($ticket->isClosed()) {
            $ticket->reopen();
        }

        return view('frontend.ticket.show', compact('ticket'));
    }

    public function closeNow($uuid)
    {

        Ticket::uuid($uuid)->close();
        notify()->success('Your Ticket Closed successfully', 'success');
        return Redirect::route('user.ticket.index');

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

        /** @var User $user */

        $user = Auth::user();

        $input = $request->all();

        $data = [
            'user_id' => $user->id, // @phpstan-ignore-line
            'message' => nl2br($input['message']),
            "attach" => $request->hasFile('attach') ? self::imageUploadTrait($input['attach']) : null,
        ];


        $ticket = Ticket::uuid($input['uuid']);


        $ticket->messages()->create($data);

        notify()->success('Your Ticket Reply successfully', 'success');

        return Redirect::route('user.ticket.show', $ticket->uuid);

    }
}
