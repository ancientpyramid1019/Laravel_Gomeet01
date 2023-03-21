@extends('backend.layouts.app')
@section('title')
    {{ __('Language Settings') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Language Settings') }}</h2>
                            <a href="{{ route('admin.language.create') }}" class="title-btn"><i
                                    icon-name="plus-circle"></i>{{ __('Add New') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-body table-responsive">
                            <div class="site-datatable">
                                <table id="dataTable" class="display data-table">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Language Name') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for Delete Language -->
                    <div
                        class="modal fade"
                        id="deleteLanguage"
                        tabindex="-1"
                        aria-labelledby="deleteLanguageModalLabel"
                        aria-hidden="true"
                    >
                        <div
                            class="modal-dialog modal-md modal-dialog-centered"
                        >
                            <div class="modal-content site-table-modal">
                                <div class="modal-body popup-body">
                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                    ></button>
                                    <div class="popup-body-text centered">
                                        <div class="info-icon">
                                            <i icon-name="alert-triangle"></i>
                                        </div>
                                        <div class="title">
                                            <h4>{{ __('Are you sure?') }}</h4>
                                        </div>
                                        <p>
                                            {{ __('You want to delete') }} <strong
                                                id="language-name"></strong> {{ __('Language?') }}
                                        </p>
                                        <div class="action-btns">
                                            <form id="deleteLanguageForm" method="post">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="site-btn-sm primary-btn me-2">
                                                    <i icon-name="check"></i>
                                                    Confirm
                                                </button>
                                                <a href="" class="site-btn-sm red-btn" type="button"
                                                   data-bs-dismiss="modal" aria-label="Close"><i
                                                        icon-name="x"></i>{{ __('Cancel') }}</a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal for Delete Language End-->

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('admin.language.index') }}",
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ]
            });


            $('body').on('click', '#deleteLanguageModal', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');

                $('#language-name').html(name);
                var url = '{{ route("admin.language.destroy", ":id") }}';
                url = url.replace(':id', id);
                $('#deleteLanguageForm').attr('action', url);
                $('#deleteLanguage').modal('toggle')

            })

        })(jQuery);
    </script>
@endsection
