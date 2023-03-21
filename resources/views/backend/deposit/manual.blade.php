@extends('backend.deposit.index')
@section('title')
    {{ __('Pending Manual Deposit') }}
@endsection
@section('deposit_content')
    <div class="col-xl-12 col-md-12">
        <div class="site-card">
            <div class="site-card-body table-responsive">
                <div class="site-datatable">
                    <table id="dataTable" class="display data-table">
                        <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Transaction ID') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Charge') }}</th>
                            <th>{{ __('Gateway') }}</th>
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
        <!-- Modal for Pending Deposit Approval -->
        @can('deposit-action')
            <div
                class="modal fade"
                id="deposit-action-modal"
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
                            <div class="popup-body-text deposit-action">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        <!-- Modal for Pending Deposit Approval -->
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
                ajax: "{{ route('admin.deposit.manual.pending') }}",
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'username', name: 'username'},
                    {data: 'tnx', name: 'tnx'},
                    {data: 'amount', name: 'amount'},
                    {data: 'charge', name: 'charge'},
                    {data: 'method', name: 'method'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ]
            });


            //send mail modal form open
            $('body').on('click', '#deposit-action', function () {
                $('.deposit-action').empty();
                console.log('ttt');

                var id = $(this).data('id');
                var url = '{{ route("admin.deposit.action",":id") }}';
                url = url.replace(':id', id);
                $.get(url, function (data) {
                    $('.deposit-action').append(data)
                    imagePreview()
                });

                $('#deposit-action-modal').modal('toggle');
            })


        })(jQuery);
    </script>
@endsection
