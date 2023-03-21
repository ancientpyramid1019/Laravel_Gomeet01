@extends('backend.layouts.app')
@section('title')
    {{ __('Counter Section') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-12">
                        <div class="title-content">
                            <h2 class="title">{{ __('Counter Section') }}</h2>
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
                            <h3 class="title">{{ __('Activity and Background') }}</h3>
                        </div>
                        <div class="site-card-body">
                            <form action="{{ route('admin.page.section.section.update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="section_code" value="counter">
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
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label">
                                        {{ __('Counter Background Image') }}
                                    </div>
                                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
                                        <div class="wrap-custom-file">
                                            <input type="file" name="counter_bg_img" id="counterBgImg"
                                                   accept=".gif, .jpg, .png"/>
                                            <label for="counterBgImg" class="file-ok"
                                                   style="background-image: url({{ asset($data->counter_bg_img) }})">
                                                <img class="upload-icon"
                                                     src="{{ asset('global/materials/upload.svg') }}" alt=""/>
                                                <span>{{ __('Update Image') }}</span>
                                            </label>
                                        </div>
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


                    <div class="site-card">
                        <div class="site-card-header">
                            <h3 class="title">{{ __('Counter Contents') }}</h3>
                            <div class="card-header-links">
                                <a href="" class="card-header-link" type="button" data-bs-toggle="modal"
                                   data-bs-target="#addNew">{{ __('Add New') }}</a>
                            </div>
                        </div>
                        <div class="site-card-body">
                            <div class="site-table table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('Icon') }}</th>
                                        <th scope="col">{{ __('Title') }}</th>
                                        <th scope="col">{{ __('Number') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($landingContent as $content)
                                        <tr>
                                            <td>
                                                <img class="avatar" src="{{ asset($content->icon) }}" alt="">
                                            </td>
                                            <td>
                                                {{ $content->title }}
                                            </td>
                                            <td>{{ $content->description }}</td>
                                            <td>
                                                <button class="round-icon-btn primary-btn editContent" type="button"
                                                        data-content="{{ json_encode($content) }}">
                                                    <i icon-name="edit-3"></i>
                                                </button>
                                                <button class="round-icon-btn red-btn deleteContent" type="button"
                                                        data-id="{{ $content->id }}">
                                                    <i icon-name="trash-2"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add New  -->
    @include('backend.page.section.include.__add_new_counter')
    <!-- Modal for Add New How It Works End -->

    <!-- Modal for Edit -->
    @include('backend.page.section.include.__edit_counter')
    <!-- Modal for Edit  End-->

    <!-- Modal for Delete  -->
    @include('backend.page.section.include.__delete_counter')
    <!-- Modal for Delete  End-->
@endsection
@section('script')
    <script>
        $('.editContent').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var content = $(this).data('content');

            imagePreviewAdd(content.icon);
            $('#updatedId').val(content.id);
            $('.title0').val(content.title);
            $('.description').val(content.description);

            $('#editContent').modal('show');
        });

        $('.deleteContent').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            $('#deleteId').val(id);
            $('#deleteContent').modal('show');
        });
    </script>
@endsection
