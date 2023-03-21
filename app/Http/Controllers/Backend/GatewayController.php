<?php

namespace App\Http\Controllers\Backend;

use App\Enums\GatewayType;
use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class GatewayController extends Controller
{

    use ImageUpload;

    function __construct()
    {
        $this->middleware('permission:manual-gateway-manage|automatic-gateway-manage', ['only' => ['edit', 'update']]);
        $this->middleware('permission:automatic-gateway-manage', ['only' => ['automatic']]);
        $this->middleware('permission:manual-gateway-manage', ['only' => ['manual', 'manualCreate', 'manualStore']]);
    }

    public function automatic()
    {

        $automaticGateways = Gateway::where('type', GatewayType::Automatic)->get();
        return view('backend.deposit.automatic_gateway', compact('automaticGateways'));
    }

    public function manual()
    {
        $button = [
            'name' => __('ADD NEW'),
            'icon' => 'plus',
            'route' => route('admin.gateway.manual.create'),
        ];
        $manualGateways = Gateway::where('type', GatewayType::Manual)->get();
        return view('backend.deposit.manual_gateway', compact('button', 'manualGateways'));
    }

    public function manualCreate()
    {
        $button = [
            'name' => __('Back'),
            'icon' => 'corner-down-left',
            'route' => route('admin.gateway.manual'),
        ];
        return view('backend.deposit.manual_gateway_create', compact('button'));
    }

    public function manualStore(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'logo' => 'required',
            'name' => 'required',
            'gateway_code' => 'required',
            'currency' => 'required',
            'currency_symbol' => 'required',
            'charge' => 'required',
            'charge_type' => 'required',
            'rate' => 'required',
            'minimum_deposit' => 'required',
            'maximum_deposit' => 'required',
            'status' => 'required',
            'credentials' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $data = [
            'logo' => self::imageUploadTrait($input['logo']),
            'name' => $input['name'],
            'gateway_code' => $input['gateway_code'],
            'currency' => $input['currency'],
            'currency_symbol' => $input['currency_symbol'],
            'charge' => $input['charge'],
            'charge_type' => $input['charge_type'],
            'rate' => $input['rate'],
            'minimum_deposit' => $input['minimum_deposit'],
            'maximum_deposit' => $input['maximum_deposit'],
            'status' => $input['status'],
            'credentials' => json_encode($input['credentials']),
            'payment_details' => Purifier::clean(htmlspecialchars_decode($input['payment_details'])),
        ];

        $gateway = Gateway::create($data);
        notify()->success($gateway->name . ' ' . __(' gateway Created'));
        return redirect()->route('admin.gateway.manual');
    }

    public function edit($code)
    {
        $gateway = Gateway::where('gateway_code', $code)->first();

        $user = \Auth::user();
        $button = [
            'name' => __('Back'),
            'icon' => 'corner-down-left',
            'route' => $gateway->type == GatewayType::Automatic ? route('admin.gateway.automatic') : route('admin.gateway.manual'),
        ];


        if ($gateway->type == GatewayType::Automatic) {
            if ($user->can('automatic-gateway-manage')) {
                return view('backend.deposit.edit_gateway', compact('gateway', 'button'));
            }
            return redirect()->route('admin.gateway.automatic');
        } else {
            if ($user->can('manual-gateway-manage')) {
                return view('backend.deposit.manual_edit_gateway', compact('gateway', 'button'));
            }
            return redirect()->route('admin.gateway.manual');
        }


    }

    public function update($id, Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'currency' => 'required',
            'currency_symbol' => 'required',
            'charge' => 'required',
            'charge_type' => 'required',
            'rate' => 'required',
            'minimum_deposit' => 'required',
            'maximum_deposit' => 'required',
            'status' => 'required',
            'credentials' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $gateway = Gateway::find($id);

        $user = \Auth::user();
        if ($gateway->type == GatewayType::Automatic) {
            if (!$user->can('automatic-gateway-manage')) {
                return redirect()->route('admin.gateway.automatic');
            }

        } else {
            if (!$user->can('manual-gateway-manage')) {
                return redirect()->route('admin.gateway.manual');
            }
        }

        $data = [
            'name' => $input['name'],
            'currency' => $input['currency'],
            'currency_symbol' => $input['currency_symbol'],
            'charge' => $input['charge'],
            'charge_type' => $input['charge_type'],
            'rate' => $input['rate'],
            'minimum_deposit' => $input['minimum_deposit'],
            'maximum_deposit' => $input['maximum_deposit'],
            'status' => $input['status'],
            'credentials' => json_encode($input['credentials']),
            'payment_details' => isset($input['payment_details']) ? Purifier::clean(htmlspecialchars_decode($input['payment_details'])) : '',

        ];

        if ($request->hasFile('logo')) {
            $logo = self::imageUploadTrait($input['logo'], $gateway->logo);
            $data = array_merge($data, ['logo' => $logo]);
        }

        $gateway->update($data);
        notify()->success($gateway->name . ' ' . __(' gateway Updated'));

        $redirectRoute = $gateway->type == GatewayType::Automatic ? 'admin.gateway.automatic' : 'admin.gateway.manual';

        return redirect()->route($redirectRoute);


    }
}
