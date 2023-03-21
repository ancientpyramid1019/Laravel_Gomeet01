@extends('frontend.layouts.user')
@section('title')
    {{ __('Withdraw Successful') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ $notify['card-header'] }}</h3>
                    <div class="card-header-links">
                        <a href="{{ route('user.withdraw.view') }}"
                           class="card-header-link">{{ __('Withdraw request') }}</a>
                    </div>
                </div>
                <div class="site-card-body">
                    <div class="progress-steps-form">
                        <div class="transaction-status centered">
                            <div class="icon success">
                                <i class="anticon anticon-check"></i>
                            </div>
                            <h2>{{$notify['title']}}</h2>
                            <p>{{$notify['p']}}</p>
                            <p>{{ $notify['strong'] }}</p>
                            <a href="{{ $notify['action'] }}" class="site-btn">
                                <i class="anticon anticon-plus"></i>{{ $notify['a'] }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
