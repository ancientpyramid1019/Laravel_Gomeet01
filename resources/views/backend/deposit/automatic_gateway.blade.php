@extends('backend.deposit.index')
@section('title')
    {{ __('Automatic Deposit') }}
@endsection
@section('deposit_content')
    <div class="col-xl-12 col-md-12">
        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">{{ __('Setup Payment Methods') }}</h3>
            </div>
            <div class="site-card-body">
                <p class="paragraph">
                    {{ __(' All the ') }}
                    <strong>{{ __('Deposit Payment Methods') }}</strong> {{ __('Setup for user') }}
                </p>
                @foreach($automaticGateways as $gateway)
                    <div class="single-gateway">
                        <div class="gateway-name">
                            <div class="gateway-icon">
                                <img
                                    src="{{ asset($gateway->logo) }}"
                                    alt=""
                                />
                            </div>
                            <div class="gateway-title">
                                <h4>{{$gateway->name}}</h4>
                                <p>{{ __('Minimum Deposit: ').$gateway->minimum_deposit .' '. $gateway->currency }}</p>
                            </div>
                        </div>
                        <div class="gateway-right">
                            <div class="gateway-status">
                                @if($gateway->status)
                                    <div class="site-badge success">{{ __('Activated') }}</div>
                                @else
                                    <div class="site-badge pending">{{ __('Deactivated') }}</div>
                                @endif
                            </div>
                            <div class="gateway-edit">
                                <a href="{{route('admin.gateway.edit-gateway',$gateway->gateway_code)}}"><i
                                        icon-name="settings-2"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </div>
@endsection
