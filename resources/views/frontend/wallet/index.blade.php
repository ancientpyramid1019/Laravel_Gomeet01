@extends('frontend.layouts.user')
@section('title')
    {{ __('Wallet Exchange') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Wallet Exchange') }}</h3>
                </div>
                <div class="site-card-body">
                    <div class="progress-steps">
                        <div class="single-step {{ $isStepOne }}">
                            <div class="number">{{ __('01') }}</div>
                            <div class="content">
                                <h4>{{ __('Wallet Details') }}</h4>
                                <p>{{ __('Enter your Wallet details') }}</p>
                            </div>
                        </div>
                        <div class="single-step {{ $isStepTwo }}">
                            <div class="number">{{ __('02') }}</div>
                            <div class="content">
                                <h4>{{ __('Success') }}</h4>
                                <p>{{  $notify['card-header'] ??  __('Successfully Exchanged') }}</p>
                            </div>
                        </div>
                    </div>
                    @yield('wallet_exchange_content')
                </div>
            </div>
        </div>
    </div>
@endsection
