@extends('frontend.layouts.user')
@section('title')
    {{ __('Add New Support Ticket') }}
@endsection
@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Add New Support Ticket') }}</h3>
                    <div class="card-header-links">
                        <a href="{{ route('user.ticket.index') }}" class="card-header-link">{{ __('All Tickets') }}</a>
                    </div>
                </div>
                <div class="site-card-body">
                    <div class="progress-steps-form">
                        <form action="{{ route('user.ticket.new-store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12 col-md-12">
                                    <label for="exampleFormControlInput1"
                                           class="form-label">{{ __('Ticket Title') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="title">
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12">
                                    <label for="exampleFormControlInput1"
                                           class="form-label">{{ __('Ticket Descriptions') }}</label>
                                    <div class="input-group">
                                        <textarea class="form-control textarea" name="message"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="wrap-custom-file">
                                    <input
                                        type="file"
                                        name="attach"
                                        id="ticket-attach"
                                        accept=".gif, .jpg, .png"
                                    />
                                    <label for="ticket-attach">
                                        <img
                                            class="upload-icon"
                                            src="{{ asset('global/materials/upload.svg') }}"
                                            alt=""
                                        />
                                        <span>{{ __('Attach Image') }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="buttons">
                                <button type="submit" class="site-btn blue-btn">
                                    {{ __('Add New Ticket') }}<i class="anticon anticon-double-right"></i>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
