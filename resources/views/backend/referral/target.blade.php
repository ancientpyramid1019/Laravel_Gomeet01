@extends('backend.layouts.app')
@section('title')
    {{ __('Set Target') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="title-content">
                            <h2 class="title">{{ __('Set Referral Target') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-md-12">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h3 class="title">{{ __('List of Targets') }}</h3>
                            <div class="card-header-links">
                                <a href="" class="card-header-link" type="button" data-bs-toggle="modal"
                                   data-bs-target="#addNewTarget">{{ __('Add New') }}</a>
                            </div>
                        </div>
                        <div class="site-card-body">
                            <p class="paragraph"><i icon-name="alert-triangle"></i>{{ __('You can') }}
                                <strong>{{ __('Add or Edit') }}</strong> {{ __('any of the') }}
                                <strong>{{ __('Targets') }}</strong></p>
                            @foreach($targets as $target)
                                <div class="single-gateway">
                                    <div class="gateway-name">
                                        <div class="gateway-title">
                                            <h4>{{ $target->name }}</h4>
                                        </div>
                                    </div>
                                    <div class="gateway-right">
                                        <div class="gateway-edit">
                                            <a href="#" type="button" class="edit-target" data-id="{{ $target->id }}"
                                               data-name="{{ $target->name }}"><i icon-name="edit-3"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Add New Target -->
        @include('backend.referral.include.__new_target')
        <!-- Modal for Add New Target-->

        <!-- Modal for Edit Target -->
        @include('backend.referral.include.__edit_target')
        <!-- Modal for Edit Target-->

    </div>
@endsection

@section('script')
    <script>
        $('.edit-target').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#target-id').val(id);
            $('.target-name').val(name);
            $('#editTarget').modal('show');

        })


    </script>
@endsection
