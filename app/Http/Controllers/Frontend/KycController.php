<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\KYCStatus;
use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Traits\ImageUpload;
use App\Traits\MailSendTrait;
use Illuminate\Http\Request;
use Validator;

class KycController extends Controller
{
    use ImageUpload, MailSendTrait;

    public function kyc()
    {
        $kycs = Kyc::where('status', true)->get();
        return view('frontend.user.kyc.index', compact('kycs'));
    }

    public function kycData($id)
    {
        $fields = Kyc::find($id)->fields;
        return view('frontend.user.kyc.data', compact('fields'))->render();
    }

    public function submit(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'kyc_id' => 'required',
            'kyc_credential' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $kyc = Kyc::find($input['kyc_id']);

        $kycCredential = array_merge($input['kyc_credential'], ['kyc_type_of_name' => $kyc->name, 'kyc_time_of_time' => now()]);

        $user = \Auth::user();

        if ($user->kyc_credential) {
            foreach (json_decode($user->kyc_credential, true) as $key => $value) {
                self::delete($value);
            }
        }

        foreach ($kycCredential as $key => $value) {

            if (is_file($value)) {
                $kycCredential[$key] = self::imageUploadTrait($value);
            }
        }

        $user->update([
            'kyc_credential' => json_encode($kycCredential),
            'kyc' => KYCStatus::Pending
        ]);


        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[email]]' => $user->email,
            '[[site_title]]' => setting('site_title','global'),
            '[[site_url]]' => route('home'),
            '[[kyc_type]]' => $kyc->name,
            '[[status]]' => 'Pending',
        ];
        $this->mailSendWithTemplate(setting('site_email','global'),'kyc_request',$shortcodes);

        notify()->success(__(' KYC Updated'));
        return redirect()->route('user.kyc');
    }
}
