@extends('frontend.layouts.user')
@section('title')
    {{ __('Send Money Logs') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('All Send Money Log') }}</h3>
                </div>
                <div class="site-card-body">
                    <div class="site-datatable">
                        <div class="row table-responsive">
                            <div class="col-xl-12">
                                <table id="dataTable" class="display data-table">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Transactions ID') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Fee') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Method') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
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
                processing: false,
                serverSide: true,
                ajax: "{{ route('user.send-money.log') }}",
                columns: [
                    {data: 'description', name: 'description'},
                    {data: 'tnx', name: 'tnx'},
                    {data: 'amount', name: 'amount'},
                    {data: 'charge', name: 'charge'},
                    {data: 'status', name: 'status'},
                    {data: 'method', name: 'method'},
                ]
            });


        })(jQuery);

    </script>
@endsection
