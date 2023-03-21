@extends('backend.layouts.app')
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title"> @yield('title')</h2>
                            @isset($button)
                                <a href="{{$button['route']}}"
                                   class="title-btn"
                                   type="button"
                                ><i icon-name="{{$button['icon']}}"></i>{{$button['name']}}</a>
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-tab-bars">
                        <ul>
                            @can('automatic-gateway-manage')
                                <li class="{{ isActive('admin.gateway.automatic') }}">
                                    <a href="{{ route('admin.gateway.automatic') }}"><i
                                            icon-name="settings-2"></i>{{ __('Automatic Gateway') }}</a>
                                </li>
                            @endcan

                            @can('manual-gateway-manage')
                                <li class="{{ isActive('admin.gateway.manual') }}">
                                    <a href="{{ route('admin.gateway.manual') }}"><i
                                            icon-name="book-open"></i>{{ __('Manual Gateway') }}</a>
                                </li>
                            @endcan
                            @canany(['deposit-list','deposit-action'])
                                <li class="{{ isActive('admin.deposit.manual.pending') }}">
                                    <a href="{{ route('admin.deposit.manual.pending') }}"><i
                                            icon-name="box"></i>{{ __('Manual Pending Deposit') }}</a>
                                </li>
                                <li class="{{ isActive('admin.deposit.history') }}">
                                    <a href="{{ route('admin.deposit.history') }}"><i
                                            icon-name="calendar"></i>{{ __('Deposit History') }}</a>
                                </li>
                            @endcanany
                        </ul>
                    </div>
                    <div class="row">
                        @yield('deposit_content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
