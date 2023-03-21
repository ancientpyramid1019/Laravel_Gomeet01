@extends('backend.layouts.app')
@section('title')
    {{ __('Pending KYC') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Pending KYC') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        <div class="site-card">
                            <div class="site-card-body table-responsive">
                                <div class="site-datatable">
                                    <table id="dataTable" class="display data-table">
                                        <thead>
                                        <tr>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('User') }}</th>
                                            <th>{{ __('Type') }}</th>
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
                        <!-- Modal for Pending KYC Details -->
                        @can('kyc-action')
                            <div
                                class="modal fade"
                                id="kyc-action-modal"
                                tabindex="-1"
                                aria-labelledby="editPendingDepositModalLabel"
                                aria-hidden="true"
                            >
                                <div class="modal-dialog modal-md modal-dialog-centered">
                                    <div class="modal-content site-table-modal">
                                        <div class="modal-body popup-body">
                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                                aria-label="Close"
                                            ></button>
                                            <div class="popup-body-text" id="kyc-action-data">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        <!-- Modal for Pending KYC Details -->
                    </div>
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
                ajax: "{{ route('admin.kyc.pending') }}",
                columns: [
                    {data: 'time', name: 'time'},
                    {data: 'user', name: 'user'},
                    {data: 'type', name: 'type'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ]
            });
        })(jQuery);

        $('body').on('click', '#action-kyc', function (e) {
            "use strict";
            e.preventDefault()
            $('#kyc-action-data').empty();

            var id = $(this).data('id');
            var url = '{{ route("admin.kyc.action",":id") }}';
            url = url.replace(':id', id);
            $.get(url, function (data) {

                $('#kyc-action-data').append(data)
                imagePreview()
            })

            $('#kyc-action-modal').modal('toggle')
        })
    </script>
@endsection
