@extends('backend.layouts.app')
@section('title')
    {{ __('Investments') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('All Investments') }}</h2>
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
                                        <th>{{ __('Icon') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Schema') }}</th>
                                        <th>{{ __('ROI') }}</th>
                                        <th>{{ __('Profit') }}</th>
                                        <th>{{ __('Capital Back') }}</th>
                                        <th>{{ __('Period Remaining') }}</th>
                                        <th>{{ __('Timeline') }}</th>
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
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.investments') }}",
                columns: [
                    {data: 'icon', name: 'icon'},
                    {data: 'username', name: 'username'},
                    {data: 'schema', name: 'schema'},
                    {data: 'rio', name: 'rio'},
                    {data: 'profit', name: 'profit'},
                    {data: 'capital_back', name: 'capital_back'},
                    {data: 'period_remaining', name: 'period_remaining'},
                    {data: 'next_profit_time', name: 'next_profit_time'},
                ]
            });


        })(jQuery);
    </script>
@endsection
