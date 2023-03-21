<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ReferralType;
use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralTarget;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReferralController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:target-manage', ['only' => ['target', 'targetStore', 'targetUpdate']]);
        $this->middleware('permission:referral-list|referral-create|referral-edit|referral-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:referral-create', ['only' => ['store']]);
        $this->middleware('permission:referral-edit', ['only' => ['update']]);
        $this->middleware('permission:referral-delete', ['only' => ['delete']]);
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $targets = ReferralTarget::all();
        $investments = Referral::type(ReferralType::Investment);
        $deposits = Referral::type(ReferralType::Deposit);
        return view('backend.referral.index', compact('targets', 'investments', 'deposits'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'referral_target_id' => Rule::unique('referrals')->where(fn($query) => $query->where('type', $request->type)),
            'target_amount' => 'required',
            'bounty' => 'required',
        ]);


        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();


        Referral::create([
            'type' => $input['type'],
            'referral_target_id' => $input['referral_target_id'],
            'bounty' => $input['bounty'],
            'target_amount' => $input['target_amount'],
            'description' => $input['description'],
        ]);

        notify()->success('Referral created successfully');
        return redirect()->route('admin.referral.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $referral = Referral::find($request->id);

        if (null != $referral) {
            $referral->delete();
        }
        notify()->success('Referral Delete successfully');
        return redirect()->route('admin.referral.index');

    }

    /**
     * @return Application|Factory|View
     */
    public function target()
    {
        $targets = ReferralTarget::all();
        return view('backend.referral.target', compact('targets'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function targetStore(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        ReferralTarget::create(['name' => $request->name]);

        notify()->success('Target created successfully');
        return redirect()->route('admin.referral.target');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function targetUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }
        $input = $request->all();
        ReferralTarget::find($input['id'])->update(['name' => $input['name']]);

        notify()->success('Target Update successfully');
        return redirect()->route('admin.referral.target');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
            'referral_target_id' => Rule::unique('referrals')->where(fn($query) => $query->where('type', $request->type))->ignore($request->id),
            'target_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'bounty' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);


        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();

        Referral::find($input['id'])->update([
            'referral_target_id' => $input['referral_target_id'],
            'target_amount' => $input['target_amount'],
            'bounty' => $input['bounty'],
            'description' => $input['description'],
        ]);

        notify()->success('Referral Updated successfully');
        return redirect()->route('admin.referral.index');
    }
}
