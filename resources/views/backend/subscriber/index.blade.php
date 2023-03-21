@extends('backend.layouts.app')
@section('title')
    {{ __('All Subscribers') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('All Subscribers') }}</h2>
                            @can('subscriber-mail-send')
                                <a href="{{ route('admin.mail.send.subscriber') }}" class="title-btn"><i
                                        icon-name="mail"></i>{{ __('Email To All') }}</a>
                            @endcan
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
                                        <th>{{ __('Subscription Date') }}</th>
                                        <th>{{ __('Email') }}</th>
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
                autoWidth: false,
                ajax: "{{ route('admin.subscriber') }}",
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'email', name: 'email'},
                ]
            });

        })(jQuery);
    </script>
@endsection
