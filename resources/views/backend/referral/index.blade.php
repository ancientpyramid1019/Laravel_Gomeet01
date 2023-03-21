@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Referral') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Set Referrals') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h3 class="title">{{ __('Investment Bounty') }}</h3>
                            @can('referral-create')
                                <div class="card-header-links">
                                    <button class="card-header-link new-referral" type="button"
                                            data-type="investment">{{ __('Add New') }}</button>
                                </div>
                            @endcan

                        </div>
                        <div class="site-card-body">
                            <p class="paragraph">{{ __('You can') }}
                                <strong>{{ __('Add').','. __('Edit').' '. __('or').' '. __('Delete') }}</strong> {{ __('any of the') }}
                                <strong>{{ __('Referred User Investment Bounty') }}</strong></p>


                            @foreach($investments as $investment)
                                <div class="single-gateway">
                                    <div class="gateway-name">
                                        <div class="gateway-title">
                                            <h4>{{ $investment->target->name }}</h4>
                                            <p>{{ $investment->description }}</p>
                                        </div>
                                    </div>
                                    <div class="gateway-right">
                                        <div class="gateway-status">
                                            <div class="site-badge success">{{ $investment->bounty }}%</div>
                                        </div>
                                        <div class="gateway-edit">
                                            @can('referral-edit')
                                                <a href="" type="button" class="edit-referral"
                                                   data-id="{{$investment->id}}"
                                                   data-type="{{$investment->type}}"
                                                   data-target="{{ $investment->referral_target_id }}"
                                                   data-target-amount="{{ $investment->target_amount }}"
                                                   data-bounty="{{ $investment->bounty }}"
                                                   data-description="{{ $investment->description }}"
                                                ><i icon-name="edit-3"></i></a>
                                            @endcan
                                            @can('referral-delete')
                                                <a href="" class="red-bg ms-2 delete-referral" type="button"
                                                   data-id="{{$investment->id}}"
                                                   data-target="{{ $investment->target->name }}"
                                                ><i icon-name="trash-2"></i></a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h3 class="title">{{ __('Deposit Bounty') }}</h3>
                            <div class="card-header-links">
                                <button class="card-header-link new-referral" type="button"
                                        data-type="deposit">{{ __('Add New') }}</button>
                            </div>
                        </div>
                        <div class="site-card-body">
                            <p class="paragraph">{{ __('You can') }}
                                <strong>{{ __('Add').','. __('Edit').' '. __('or').' '. __('Delete') }}</strong> {{ __('any of the') }}
                                <strong>{{ __('Referred User Deposit Bounty') }}</strong></p>
                            @foreach($deposits as $deposit)

                                <div class="single-gateway">
                                    <div class="gateway-name">
                                        <div class="gateway-title">
                                            <h4>{{ $deposit->target->name }}</h4>
                                            <p>{{ $deposit->description }}</p>
                                        </div>
                                    </div>
                                    <div class="gateway-right">
                                        <div class="gateway-status">
                                            <div class="site-badge success">{{ $deposit->bounty .''.__('%')}}</div>
                                        </div>
                                        <div class="gateway-edit">
                                            @can('referral-edit')
                                                <a href="" type="button" class="edit-referral"
                                                   data-id="{{$deposit->id}}"
                                                   data-type="{{$deposit->type}}"
                                                   data-target="{{ $deposit->referral_target_id }}"
                                                   data-target-amount="{{ $deposit->target_amount }}"
                                                   data-bounty="{{ $deposit->bounty }}"
                                                   data-description="{{ $deposit->description }}"
                                                ><i icon-name="edit-3"></i></a>
                                            @endcan
                                            @can('referral-delete')
                                                <a href="" class="red-bg ms-2 delete-referral" type="button"
                                                   data-id="{{ $deposit->id }}"
                                                   data-target="{{ $deposit->target->name }}"
                                                ><i icon-name="trash-2"></i></a>
                                            @endcan</div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Add New Level -->
        @can('referral-create')
            @include('backend.referral.include.__new_referral')
        @endcan
        <!-- Modal for Add New Level-->

        <!-- Modal for Edit Level -->
        @can('referral-edit')
            @include('backend.referral.include.__edit_referral')
        @endcan
        <!-- Modal for Edit Level-->

        <!-- Modal for Delete Level -->
        @can('referral-delete')
            @include('backend.referral.include.__delete_referral')
        @endcan
        <!-- Modal for Delete Level End-->

        @endsection
        @section('script')
            <script>

                $('.new-referral').on('click',function (e) {
                    "use strict";
                    e.preventDefault();
                    var type = $(this).data('type');
                    $('.referral-type').val(type);
                    $('#addNewReferral').modal('show');

                })

                $('.edit-referral').on('click',function (e) {
                    "use strict";
                    e.preventDefault();
                    var id = $(this).data('id');
                    var type = $(this).data('type');
                    var target = $(this).data('target');
                    var targetAmount = $(this).data('target-amount');
                    var bounty = $(this).data('bounty');
                    var description = $(this).data('description');


                    $('.referral-id').val(id);
                    $('.referral-type').val(type);
                    $('.target_id').val(target);
                    $('.target-amount').val(targetAmount);
                    $('.bounty').val(bounty);
                    $('.description').val(description);

                    $('#editReferral').modal('show');

                })
                $('.delete-referral').on('click',function (e) {
                    "use strict";
                    e.preventDefault();
                    var id = $(this).data('id');
                    var target = $(this).data('target');
                    $('.referral-id').val(id);
                    $('.target').html(target);
                    $('#deleteReferral').modal('show');

                })

            </script>
@endsection
