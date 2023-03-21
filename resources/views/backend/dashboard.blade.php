@php use App\Enums\InvestStatus; @endphp
@extends('backend.layouts.app')
@section('title')
    {{ __('Dashboard') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ setting('site_title', 'global') }} {{ __('Dashboard') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                @canany(['deposit-action','withdraw-action','kyc-action',])
                    @if($data['withdraw_count'] || $data['kyc_count'] || $data['deposit_count'])
                        <div class="col-xl-12">
                            <div class="admin-latest-announcements">
                                <div class="content"><i
                                        icon-name="zap"></i>{{ __("Explore what's important to review first") }}</div>
                                <div class="content">
                                    @can('withdraw-action')
                                        @if($data['withdraw_count'])
                                            <a href="{{ route('admin.withdraw.pending') }}" class="site-btn-xs red-btn"><i
                                                    icon-name="loader"
                                                    class="spining-icon"></i>{{ __('Withdraw Requests') }}
                                                ({{ $data['withdraw_count'] }})</a>
                                        @endif
                                    @endcan

                                    @can('kyc-action')
                                        @if($data['kyc_count'])
                                            <a href="{{ route('admin.kyc.pending') }}" class="site-btn-xs green-btn"><i
                                                    icon-name="loader" class="spining-icon"></i>{{ __('KYC Requests') }}
                                                ({{ $data['kyc_count'] }})</a>
                                        @endif
                                    @endcan

                                    @can('deposit-action')
                                        @if($data['deposit_count'])
                                            <a href="{{ route('admin.deposit.manual.pending') }}"
                                               class="site-btn-xs primary-btn"><i icon-name="loader"
                                                                                  class="spining-icon"></i>{{ __('Deposit Requests') }}
                                                ({{ $data['deposit_count'] }})</a>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endif
            </div>
            @endcanany

            @include('backend.include.__data_card')

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="site-chart">
                        <div class="site-card">
                            <div class="site-card-header">
                                <h3 class="title">{{ __('Weekly Deposit') }}</h3>
                                <div class="card-header-links">
                                    <h3 class="title-small">{{ $data['date_range']}}</h3>
                                </div>
                            </div>
                            <div class="site-card-body">
                                <canvas id="depositChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="site-chart">
                        <div class="site-card">
                            <div class="site-card-header">
                                <h3 class="title">{{ __('Weekly Investment') }}</h3>
                                <div class="card-header-links">
                                    <h3 class="title-small">{{ $data['date_range']}}</h3>
                                </div>
                            </div>
                            <div class="site-card-body">
                                <canvas id="investChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h3 class="title">{{ __('Latest Registered User') }}</h3>
                        </div>
                        <div class="site-card-body table-responsive">
                            <div class="site-datatable">
                                <table class="data-table mb-0">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Avatar') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Balance') }}</th>
                                        <th>{{ __('Profit') }}</th>
                                        <th>{{ __('KYC') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['latest_user'] as $user)
                                        <tr>
                                            <td>
                                                @if(null != $user->avatar)
                                                    <img class="avatar" src="{{ asset($user->avatar)}}" alt=""
                                                         height="40" width="40">
                                                @else
                                                    <span
                                                        class="avatar-text">{{ $user->first_name[0] }}{{ $user->last_name[0] }}</span>
                                                @endif
                                            </td>
                                            <td><a href="{{ route('admin.user.edit',$user->id) }}"
                                                   class="link">{{ safe($user->username) }}</a></td>
                                            <td>
                                                <strong>{{ safe($user->email) }}</strong>
                                            </td>
                                            <td><strong>{{ $currencySymbol . $user->balance }}</strong></td>
                                            <td><strong>{{ $currencySymbol . $user->total_profit }}</strong></td>
                                            <td>
                                                @if($user->kyc == 1)
                                                    <div class="site-badge success">{{ __('Verified') }}</div>
                                                @else
                                                    <div class="site-badge pending">{{ __('Unverified') }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->status == 1)
                                                    <div class="site-badge success">{{ __('Active') }}</div>
                                                @else
                                                    <div class="site-badge danger">{{ __('DeActivated') }}</div>
                                                @endif
                                            </td>
                                            <td>

                                                <a href="{{route('admin.user.edit',$user->id)}}"
                                                   class="round-icon-btn primary-btn" data-bs-toggle="tooltip" title=""
                                                   data-bs-original-title="Edit User"><i icon-name="edit-3"></i></a>
                                                <span type="button"
                                                      data-id="{{$user->id}}"
                                                      data-name="{{ $user->first_name.' '. $user->last_name }}"
                                                      class="send-mail"
                                                ><button class="round-icon-btn red-btn" data-bs-toggle="tooltip"
                                                         title="" data-bs-original-title="Send Email"><i
                                                            icon-name="mail"></i></button></span>

                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="centered">
                                        <td colspan="7">
                                            @if($data['latest_user']->isEmpty())
                                                {{ __('No Data Found') }}
                                            @endif
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h3 class="title">{{ __('Latest Investment') }}</h3>
                        </div>
                        <div class="site-card-body table-responsive">
                            <div class="site-datatable">
                                <table class="data-table mb-0">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Avatar') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Schema') }}</th>
                                        <th>{{ __('ROI') }}</th>
                                        <th>{{ __('Profit') }}</th>
                                        <th>{{ __('Capital Back') }}</th>
                                        <th>{{ __('Timeline') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['latest_invest'] as $invest)

                                        @php
                                            $calculateInterest = ($invest->interest*$invest->invest_amount)/100;
                                            $interest = $invest->interest_type != 'percentage' ? $invest->interest : $calculateInterest;
                                        @endphp


                                        <tr>
                                            <td>
                                                @if(null != $invest->user->avatar)
                                                    <img class="avatar" src="{{ asset($invest->user->avatar)}}" alt=""
                                                         height="40" width="40">
                                                @else
                                                    <span
                                                        class="avatar-text">{{ $invest->user->first_name[0] }}{{ $invest->user->last_name[0] }}</span>
                                                @endif
                                            </td>
                                            <td><a href="{{ route('admin.user.edit',$invest->user_id) }}"
                                                   class="link">{{ safe($invest->user->username) }}</a></td>
                                            <td>

                                                <strong> {{ $invest->schema->name }} <i
                                                        icon-name="arrow-big-right"></i> {{ $currencySymbol.$invest->invest_amount }}
                                                </strong>

                                            </td>
                                            <td>
                                                <strong>{{ $invest->interest_type == 'percentage' ? $invest->interest.'%' : $currencySymbol.$invest->interest }}</strong>
                                            </td>

                                            <td>
                                                <strong>{{ $invest->already_return_profit .' x '.$invest->interest .' = '. ($invest->already_return_profit*$invest->interest).' '. $currency }}</strong>
                                            </td>
                                            <td>
                                                <div
                                                    class="site-badge {{ $invest->capital_back ? 'success' : 'pending' }}">{{ $invest->capital_back ? 'Yes' : 'No' }}</div>
                                            </td>
                                            <td>

                                                @if($invest->status == InvestStatus::Ongoing)

                                                    <div>
                                                        <strong><span id="days{{ $invest->id }}"></span>D : <span
                                                                id="hours{{ $invest->id }}"></span>H : <span
                                                                id="minutes{{ $invest->id }}"></span>M : <span
                                                                id="seconds{{ $invest->id }}"></span>S</strong>
                                                        <span class="site-badge primary-bg ms-2"
                                                              id="percentage{{ $invest->id }}"></span>
                                                    </div>
                                                    <div class="progress investment-timeline">
                                                        <div
                                                            class="progress-bar progress-bar-striped progress-bar-animated"
                                                            id="time-progress{{ $invest->id }}" role="progressbar"
                                                            aria-valuenow="75" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>

                                                    @push('single-script')
                                                        <script>
                                                            (function ($) {
                                                                "use strict";
                                                                // Countdown
                                                                const second = 1000,
                                                                    minute = second * 60,
                                                                    hour = minute * 60,
                                                                    day = hour * 24;


                                                                let countDown = new Date('{{$invest->next_profit_time}}').getTime()
                                                                var start = new Date('{{ $invest->last_profit_time ?? $invest->created_at}}').getTime()
                                                                setInterval(function () {

                                                                    let utc_datetime_str = new Date().toLocaleString("en-US", {timeZone: "UTC"});
                                                                    let now = new Date(utc_datetime_str).getTime();
                                                                    let distance = countDown - now;


                                                                    var progress = (((now - start) / (countDown - start)) * 100).toFixed(2);


                                                                    $("#time-progress{{ $invest->id }}").css("width", progress + '%');

                                                                    $("#percentage{{ $invest->id }}").text(progress >= 100 ? 100 + '%' : progress + '%');

                                                                    document.getElementById('days{{ $invest->id }}').innerText = Math.floor(distance < 0 ? 0 : distance / (day)),
                                                                        document.getElementById('hours{{ $invest->id }}').innerText = Math.floor(distance < 0 ? 0 : (distance % (day)) / (hour)),
                                                                        document.getElementById('minutes{{$invest->id }}').innerText = Math.floor(distance < 0 ? 0 : (distance % (hour)) / (minute)),
                                                                        document.getElementById('seconds{{ $invest->id }}').innerText = Math.floor(distance < 0 ? 0 : (distance % (minute)) / second);

                                                                }, second)

                                                            })(jQuery)
                                                        </script>
                                                    @endpush

                                                @elseif($invest->status == InvestStatus::Completed)

                                                    <div class="site-badge success">{{ __('Completed') }}</div>
                                                    <div class="progress investment-timeline">
                                                        <div
                                                            class="progress-bar progress-bar-striped progress-bar-animated"
                                                            role="progressbar" aria-valuenow="75" aria-valuemin="0"
                                                            aria-valuemax="100" style="width: 100%"></div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="centered">
                                        <td colspan="7">
                                            @if($data['latest_invest']->isEmpty())
                                                {{ __('No Data Found') }}
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Modal for Send Email -->
    @include('backend.user.include.__mail_send')
    <!-- Modal for Send Email-->

@endsection
@section('script')
    <script>
        (function ($) {
            'use strict';

            //deposit chart
            var deposit = @json($data['last7days_deposit']);

            var deposit_label = Object.keys(deposit);
            var deposit_data = Object.values(deposit);

            // Bar Chart
            var data = {
                labels: deposit_label,
                datasets: [{
                    label: 'Weekly Deposit',
                    data: deposit_data,
                    backgroundColor: [
                        '#5e3fc9',
                        '#d2d3d8',
                        '#5e3fc9',
                        '#d2d3d8',
                        '#5e3fc9',
                        '#d2d3d8',
                        '#5e3fc9'
                    ],
                    borderColor: [
                        '#5e3fc9',
                        '#d2d3d8',
                        '#5e3fc9',
                        '#d2d3d8',
                        '#5e3fc9',
                        '#d2d3d8',
                        '#5e3fc9'
                    ],
                    borderWidth: 0,
                    borderRadius: 3,
                    barPercentage: 0.3,
                    hoverBackgroundColor: '#5e3fc9',
                }]
            };
            // render init block
            new Chart(
                document.getElementById('depositChart'),
                {
                    type: 'bar',
                    data,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                }
            );


            //investment chart

            var invest = @json($data['last7days_invest']);

            var invest_label = Object.keys(invest);
            var invest_data = Object.values(invest);

            // Bar Chart
            var data = {
                labels: invest_label,
                datasets: [{
                    label: 'Weekly Investment',
                    data: invest_data,
                    backgroundColor: [
                        '#5e3fc9',
                        '#d2d3d8',
                        '#5e3fc9',
                        '#d2d3d8',
                        '#5e3fc9',
                        '#d2d3d8',
                        '#5e3fc9'
                    ],
                    borderColor: [
                        '#5e3fc9',
                        '#d2d3d8',
                        '#5e3fc9',
                        '#d2d3d8',
                        '#5e3fc9',
                        '#d2d3d8',
                        '#5e3fc9'
                    ],
                    borderWidth: 0,
                    borderRadius: 3,
                    barPercentage: 0.3,
                    hoverBackgroundColor: '#5e3fc9',
                }]
            };
            // render init block
            new Chart(
                document.getElementById('investChart'),
                {
                    type: 'bar',
                    data,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                }
            );

            //send mail modal form open
            $('.send-mail').on('click', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#name').html(name);
                $('#userId').val(id);
                $('#sendEmail').modal('toggle')
            })

        })(jQuery);
    </script>
@endsection
