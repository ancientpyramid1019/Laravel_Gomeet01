<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Rules\MatchOldPassword;
use App\Traits\ImageUpload;
use App\Traits\MailSendTrait;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    use ImageUpload, MailSendTrait;

    function __construct()
    {
        $this->middleware('permission:subscriber-list|subscriber-mail-send', ['only' => ['subscribers']]);
        $this->middleware('permission:subscriber-mail-send', ['only' => ['mailSendSubscriber', 'mailSendSubscriberNow']]);

    }

    public function subscribers(Request $request)
    {
        if ($request->ajax()) {

            $data = Subscription::query();

            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.subscriber.index');
    }

    public function mailSendSubscriber()
    {
        return view('backend.subscriber.mail_send');
    }

    public function mailSendSubscriberNow(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        try {


            $input = [
                'subject' => $request->subject,
                'message' => $request->message,
            ];

            $shortcodes = [
                '[[subject]]' => $input['subject'],
                '[[message]]' => $input['message'],
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];

            $subscribers = Subscription::all();
            foreach ($subscribers as $subscriber) {

                $this->mailSendWithTemplate($subscriber->email, 'subscriber_mail', $shortcodes);
            }

            $status = 'success';
            $message = __('Mail Send Successfully');

        } catch (Exception $e) {
            $status = 'warning';
            $message = __('something is wrong');
        }

        notify()->$status($message, $status);
        return redirect()->back();
    }

    public function profile()
    {
        return view('backend.profile.profile');
    }

    public function profileUpdate(Request $request)
    {

        $user = \Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:admins,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }
        auth()->user()->update([
            'avatar' => $request->hasFile('avatar') ? self::imageUploadTrait($request->avatar, $user->avatar) : $user->avatar,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);
        notify()->success('Profile Update Successfully');
        return redirect()->back();
    }

    public function passwordChange()
    {
        return view('backend.profile.password_change');
    }

    public function passwordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        auth()->user()->update(['password' => Hash::make($request->new_password)]);
        notify()->success('Password Changed Successfully');
        return redirect()->back();
    }

    public function applicationInfo()
    {
        $applicationInfo = [
            'PHP Version' => 8.1,
            'Laravel Version' => 9.3,
            'Site Name' => setting('site_title', 'global'),
            'Debug Mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'Site Mode' => config('app.env') == 'local' ? 'Testing' : 'Production',
            'Database Port' => config('database.connections.mysql.port'),

            'Server IP Address' => $_SERVER['SERVER_ADDR'],
            'Server Protocol' => $_SERVER['SERVER_PROTOCOL'],
        ];

        return view('backend.system.index', compact('applicationInfo'));
    }

    public function clearCache()
    {
        notify()->success('Clear Cache Successfully');
        \Artisan::call('optimize:clear');
        return redirect()->back();
    }
}
