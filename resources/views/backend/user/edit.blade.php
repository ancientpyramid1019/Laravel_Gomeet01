@extends('backend.layouts.app')
@section('title')
    {{ __('Customer Details') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Details of')   .' '. $user->first_name .' '.  $user->last_name }} </h2>
                            <a href="{{ url()->previous() }}" class="title-btn"><i
                                    icon-name="corner-down-left"></i>{{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xxl-3 col-xl-6 col-lg-8 col-md-6 col-sm-12">
                    <div class="profile-card">
                        <div class="top">
                            <div class="avatar">
                                @if(null != $user->avatar)
                                    <img
                                        class="avatar-image"
                                        src="{{asset($user->avatar)}}"
                                        alt="{{$user->first_name}}"
                                    />
                                @else
                                    <span class="avatar-text">{{$user->first_name[0] .$user->last_name[0] }}</span>
                                @endif
                            </div>
                            <div class="title-des">
                                <h4>{{$user->first_name .' '. $user->last_name}}</h4>
                                <p>{{$user->city}}, {{ $user->country }}</p>
                            </div>
                            <div class="btns">
                                @can('customer-mail-send')
                                    <span type="button" data-bs-toggle="modal" data-bs-target="#sendEmail"><a
                                            href="javascript:void(0);" class="site-btn-round blue-btn"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Send Email"><i
                                                icon-name="mail"></i></a></span>
                                @endcan
                                @can('customer-login')
                                    <a href="{{ route('admin.user.login',$user->id) }}" target="_blank"
                                       class="site-btn-round red-btn" data-bs-toggle="tooltip" title=""
                                       data-bs-placement="top" data-bs-original-title="Login As User">
                                        <i icon-name="user-plus"></i>
                                    </a>
                                @endcan
                                @can('customer-balance-add-or-subtract')
                                    <span data-bs-toggle="modal" data-bs-target="#addSubBal">
                                    <a href="javascript:void(0);" type="button" class="site-btn-round primary-btn"
                                       data-bs-toggle="tooltip" title="" data-bs-placement="top"
                                       data-bs-original-title="Fund Add or Subtract">
                                    <i icon-name="wallet"></i></a></span>
                                @endcan
                            </div>
                        </div>
                        <div class="site-card">
                            <div class="site-card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="admin-user-balance-card">
                                            <div class="wallet-name">
                                                <div class="name">{{ __('Main Wallet') }}</div>
                                                <div class="chip-icon">
                                                    <img class="chip"
                                                        src="{{asset('backend/materials/chip.png')}}"
                                                        alt=""
                                                    />
                                                </div>
                                            </div>
                                            <div class="wallet-info">
                                                <div class="wallet-id">{{ setting('site_currency','global') }}</div>
                                                <div
                                                    class="balance">{{ setting('currency_symbol','global') . $user->balance }}</div>
                                            </div>
                                        </div>
                                        <div class="admin-user-balance-card">
                                            <div class="wallet-name">
                                                <div class="name">{{ __('Profit Wallet') }}</div>
                                                <div class="chip-icon">
                                                    <img
                                                        class="chip"
                                                        src="{{asset('backend/materials/chip.png')}}"
                                                        alt=""
                                                    />
                                                </div>
                                            </div>
                                            <div class="wallet-info">
                                                <div class="wallet-id">{{ setting('site_currency','global') }}</div>
                                                <div
                                                    class="balance">{{ setting('currency_symbol','global') . $user->profit_balance }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Status Update -->
                        @can('all-type-status')
                            @include('backend.user.include.__status_update')
                        @endcan
                        <!-- User Status Update End-->

                    </div>
                </div>


                <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="site-tab-bars">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            @canany(['customer-basic-manage','customer-change-password'])
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link active"
                                        id="pills-informations-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-informations"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-informations"
                                        aria-selected="true"
                                    ><i icon-name="user"></i>{{ __('Informations') }}</a>
                                </li>
                            @endcanany
                            @can('transaction-list')
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link"
                                        id="pills-transactions-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-transactions"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-transactions"
                                        aria-selected="true"
                                    ><i icon-name="cast"></i>{{ __('Transactions') }}</a>
                                </li>
                            @endcan

                            @can('profit-list')
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link"
                                        id="pills-deposit-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-deposit"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-deposit"
                                        aria-selected="true"
                                    ><i icon-name="credit-card"></i>{{ __('Earnings') }}</a>
                                </li>
                            @endcan
                            @can('investment-list')
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link"
                                        id="pills-transfer-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-transfer"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-transfer"
                                        aria-selected="true"
                                    ><i icon-name="anchor"></i>{{ __('Investments') }}</a>
                                </li>
                            @endcan
                            @canany(['support-ticket-list','support-ticket-action'])
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link"
                                        id="pills-ticket-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-ticket"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-transfer"
                                        aria-selected="true"
                                    ><i icon-name="wrench"></i>{{ __('Ticket') }}</a>
                                </li>
                            @endcanany

                        </ul>
                    </div>

                    <div class="tab-content" id="pills-tabContent">
                        <!-- basic Info -->
                        @canany(['customer-basic-manage','customer-change-password'])
                            @include('backend.user.include.__basic_info')
                        @endcanany

                        <!-- transaction -->
                        @can('transaction-list')
                            @include('backend.user.include.__transactions')
                        @endcan

                        <!-- earnings -->
                        @can('profit-list')
                            @include('backend.user.include.__earnings')
                        @endcan

                        <!-- investments -->
                        @can('investment-list')
                            @include('backend.user.include.__investments')
                        @endcan

                        <!-- investments -->
                        @canany(['support-ticket-list','support-ticket-action'])
                            @include('backend.user.include.__ticket')
                        @endcan

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Send Email -->
    @can('customer-mail-send')
        @include('backend.user.include.__mail_send',['name' => $user->first_name.' '.$user->last_name, 'id' => $user->id])
    @endcan
    <!-- Modal for Send Email-->

    <!-- Modal for Add or Subtract Balance -->
    @can('customer-balance-add-or-subtract')
        @include('backend.user.include.__balance')
    @endcan
    <!-- Modal for Add or Subtract Balance End-->

@endsection
