@extends('backend.layouts.app')
@section('title')
    {{ __('Page Settings') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="title-content">
                            <h2 class="title">{{ __('Page Settings') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h3 class="title">{{ __('Basic Settings') }}</h3>
                        </div>
                        <div class="site-card-body">
                            <form action="{{ route('admin.page.setting.update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="site-input-groups row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label">
                                        {{ __('Page Breadcrumb') }}<i icon-name="info" data-bs-toggle="tooltip" title=""
                                                                      data-bs-original-title="All the pages Breadcrumb Background Image"></i>
                                    </div>
                                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
                                        <div class="wrap-custom-file">
                                            <input type="file" name="breadcrumb" id="breadcrumbBg"
                                                   accept=".gif, .jpg, .png"/>
                                            <label for="breadcrumbBg" class="file-ok"
                                                   style="background-image: url({{ asset(getPageSetting('breadcrumb')) }})">
                                                <img class="upload-icon"
                                                     src="{{ asset('global/materials/upload.svg') }}" alt=""/>
                                                <span>{{ __('Update Background') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="offset-sm-3 col-sm-9">
                                        <button type="submit"
                                                class="site-btn-sm primary-btn">{{ __('Save Changes') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
