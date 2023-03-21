@extends('backend.layouts.app')
@section('title')
    {{ __('Calculation Section') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-12">
                        <div class="title-content">
                            <h2 class="title">{{ __('Calculation Section') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h3 class="title">Contents</h3>
                        </div>
                        <div class="site-card-body">

                            <form action="{{ route('admin.page.section.section.update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="section_code" value="calculation">
                                <div class="site-input-groups row">
                                    <label for="" class="col-sm-3 col-label pt-0">{{ __('Section Activity') }}<i
                                            icon-name="info" data-bs-toggle="tooltip" title=""
                                            data-bs-original-title="Manage Section Visibility"></i></label>
                                    <div class="col-sm-3">
                                        <div class="site-input-groups">
                                            <div class="switch-field">
                                                <input type="radio" id="active" name="status" @if($status) checked
                                                       @endif value="1"/>
                                                <label for="active">{{ __('Show') }}</label>
                                                <input type="radio" id="deactivate" name="status" @if(!$status) checked
                                                       @endif value="0"/>
                                                <label for="deactivate">{{ __('Hide') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="site-input-groups row">
                                    <label for="" class="col-sm-3 col-label">{{ __('Calculation Title Small') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="calculation_title_small" class="box-input"
                                               value="Investment Calculator">
                                    </div>
                                </div>
                                <div class="site-input-groups row">
                                    <label for="" class="col-sm-3 col-label">{{ __('Calculation Title Big') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="calculation_title_big" class="box-input"
                                               value="Investment Calculation.">
                                    </div>
                                </div>
                                <div class="site-input-groups row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label">
                                        {{ __('Calculation Left Image') }}
                                    </div>
                                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
                                        <div class="wrap-custom-file">
                                            <input type="file" name="calculation_left_img" id="heroRightImg"
                                                   accept=".gif, .jpg, .png"/>
                                            <label for="heroRightImg" class="file-ok"
                                                   style="background-image: url({{ asset($data->calculation_left_img) }})">
                                                <img class="upload-icon"
                                                     src="{{ asset('global/materials/upload.svg') }}" alt=""/>
                                                <span>{{ __('Update Image') }}</span>
                                            </label>
                                        </div>
                                    </div>


                                </div>
                                <div class="site-input-groups row">
                                    <label for="" class="col-sm-3 col-label">{{ __('Video URL') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="intro_video" class="box-input" value="{{ $data->intro_video }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="offset-sm-3 col-sm-9">
                                        <button type="submit"
                                                class="site-btn-sm primary-btn w-100">{{ __('Save Changes') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="section-design-nb">
                        <strong>{{ __('NB:') }}</strong>{{ __('Calculations will come from') }} <a
                            href="{{ route('admin.schema.index') }}">{{ __('Schemas') }}</a></div>
                </div>
            </div>
        </div>
    </div>
@endsection
