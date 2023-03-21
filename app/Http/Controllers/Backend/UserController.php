<?php

namespace App\Http\Controllers\Backend;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\MailSendTrait;
use DataTables;
use Exception;
use Hash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Txn;

class UserController extends Controller
{

    use MailSendTrait;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:customer-list|customer-login|customer-mail-send|customer-basic-manage|customer-change-password|all-type-status|customer-balance-add-or-subtract', ['only' => ['index', 'activeUser', 'disabled', 'mailSendAll', 'mailSend']]);
        $this->middleware('permission:customer-basic-manage|customer-change-password|all-type-status|customer-balance-add-or-subtract', ['only' => ['edit']]);
        $this->middleware('permission:customer-login', ['only' => ['userLogin']]);
        $this->middleware('permission:customer-mail-send', ['only' => ['mailSendAll', 'mailSend']]);
        $this->middleware('permission:customer-basic-manage', ['only' => ['update']]);
        $this->middleware('permission:customer-change-password', ['only' => ['passwordUpdate']]);
        $this->middleware('permission:all-type-status', ['only' => ['statusUpdate']]);
        $this->middleware('permission:customer-balance-add-or-subtract', ['only' => ['balanceUpdate']]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = User::latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->editColumn('balance', function ($request) {
                    return $request->balance . ' ' . setting('site_currency');
                })
                ->editColumn('email', function ($request) {
                    return safe($request->email);
                })
                ->editColumn('username', function ($request) {
                    return safe($request->username);
                })
                ->editColumn('total_profit', function ($request) {
                    return $request->total_profit . ' ' . setting('site_currency');
                })
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['avatar', 'kyc', 'status', 'action'])
                ->make(true);
        }
        return view('backend.user.index');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function activeUser(Request $request)
    {
        if ($request->ajax()) {

            $data = User::where('status', 1)->latest();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->editColumn('balance', function ($request) {
                    return $request->balance . ' ' . setting('site_currency');
                })
                ->editColumn('total_profit', function ($request) {
                    return $request->total_profit . ' ' . setting('site_currency');
                })
                ->editColumn('email', function ($request) {
                    return safe($request->email);
                })
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['avatar', 'kyc', 'status', 'action'])
                ->make(true);
        }
        return view('backend.user.active_user');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function disabled(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('status', 0)->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->editColumn('balance', function ($request) {
                    return $request->balance . ' ' . setting('site_currency');
                })
                ->editColumn('email', function ($request) {
                    return safe($request->email);
                })
                ->editColumn('total_profit', function ($request) {
                    return $request->total_profit . ' ' . setting('site_currency');
                })
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['avatar', 'kyc', 'status', 'action'])
                ->make(true);
        }
        return view('backend.user.disabled_user');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('backend.user.edit', compact('user'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function statusUpdate($id, Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'status' => 'required',
            'email_verified' => 'required',
            'kyc' => 'required',
            'two_fa' => 'required',
            'deposit_status' => 'required',
            'withdraw_status' => 'required',
            'transfer_status' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $data = [
            'status' => $input['status'],
            'kyc' => $input['kyc'],
            'two_fa' => $input['two_fa'],
            'deposit_status' => $input['deposit_status'],
            'withdraw_status' => $input['withdraw_status'],
            'transfer_status' => $input['transfer_status'],
            'email_verified_at' => $input['email_verified'] == 1 ? now() : NULL,
        ];

        $user = User::find($id);

        if ($user->status != $input['status'] && !$input['status']) {

            $shortcodes = [
                '[[full_name]]' => $user->full_name,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];

            $this->mailSendWithTemplate($user->email, 'user_account_disabled', $shortcodes);
        }


        User::find($id)->update($data);

        notify()->success('Status Updated Successfully', 'success');
        return redirect()->back();


    }

    /**
     * @param $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function update($id, Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users,username,' . $id,
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $validated = $validator->safe()->only(['first_name', 'last_name', 'username', 'city', 'zip_code', 'address']);

        User::find($id)->update($validated);
        notify()->success('User Info Updated Successfully', 'success');
        return redirect()->back();
    }

    /**
     * @param $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function passwordUpdate($id, Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $password = $validator->validated();

        User::find($id)->update([
            'password' => Hash::make($password['new_password'])
        ]);
        notify()->success('User Password Updated Successfully', 'success');
        return redirect()->back();
    }

    /**
     * @param $id
     * @param Request $request
     * @return RedirectResponse|void
     */
    public function balanceUpdate($id, Request $request)
    {


        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        try {

            $amount = $request->amount;
            $type = $request->type;


            $user = User::find($id);
            $adminUser = \Auth::user();

            if ($type == 'add') {
                $user->balance += $amount;
                $user->save();

                Txn::new($amount, 0, $amount, 'system', 'Money Deposit From Admin', TxnType::Deposit, TxnStatus::Success, null, null, $id, $adminUser->id, 'Admin');


                $status = 'success';
                $message = __('Account Balance Update');

            } elseif ($type == 'subtract') {

                $user->balance -= $amount;
                $user->save();
                Txn::new($amount, 0, $amount, 'system', 'Money Subtract From Admin', TxnType::Subtract, TxnStatus::Success, null, null, $id, $adminUser->id, 'Admin');
                $status = 'success';
                $message = __('Account Balance Updated');
            }

            notify()->success($message, $status);
            return redirect()->back();

        } catch (Exception $e) {
            $status = 'warning';
            $message = __('something is wrong');
            $code = 503;
        }


    }


    /**
     * @return Application|Factory|View
     */
    public function mailSendAll()
    {
        return view('backend.user.mail_send_all');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function mailSend(Request $request)
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

            if (isset($request->id)) {
                $user = User::find($request->id);

                $shortcodes = array_merge($shortcodes, ['[[full_name]]' => $user->full_name]);

                $this->mailSendWithTemplate($user->email, 'user_mail', $shortcodes);

            } else {
                $users = User::where('status', 1)->get();

                foreach ($users as $user) {
                    $shortcodes = array_merge($shortcodes, ['[[full_name]]' => $user->full_name]);

                    $this->mailSendWithTemplate($user->email, 'user_mail', $shortcodes);
                }

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

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse|void
     * @throws Exception
     */
    public function transaction($id, Request $request)
    {

        if ($request->ajax()) {
            $data = Transaction::where('user_id', $id)->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', 'backend.user.include.__txn_status')
                ->editColumn('type', 'backend.user.include.__txn_type')
                ->editColumn('final_amount', 'backend.user.include.__txn_amount')
                ->rawColumns(['status', 'type', 'final_amount'])
                ->make(true);
        }
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function userLogin($id)
    {
        Auth::guard('web')->loginUsingId($id);
        return redirect()->route('user.dashboard');
    }
}
