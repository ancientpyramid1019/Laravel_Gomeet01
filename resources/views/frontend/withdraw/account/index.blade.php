@extends('frontend.layouts.user')
@section('title')
    {{ __('Withdraw Account') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Withdraw Account') }}</h3>
                    <div class="card-header-links">
                        <a href="{{ route('user.withdraw.account.create') }}"
                           class="card-header-link">{{ __('Add New') }}</a>
                    </div>
                </div>
                <div class="site-card-body">
                    <div class="site-transactions">
                        @foreach($accounts as $account)
                            <div class="single">
                                <div class="left">
                                    <div class="icon">
                                        <i icon-name="clipboard-check"></i>
                                    </div>
                                    <div class="content">
                                        <div class="title">{{$account->method_name}}</div>
                                        <div class="date">{{ $account->method->currency .' '. __('Account') }} </div>
                                    </div>
                                </div>
                                <div class="right">
                                    <div class="action">
                                        <a href="{{ route('user.withdraw.account.edit',$account->id) }}"><i icon-name="edit"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
