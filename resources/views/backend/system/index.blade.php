@extends('backend.layouts.app')
@section('title')
    {{ __('Application Details') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Application Details') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-body">
                            <div class="site-table table-responsive mb-0">
                                <table class="table mb-0">
                                    <tbody>
                                    @foreach($applicationInfo as $key => $value)
                                        <tr>
                                            <td>
                                                <strong>{{  $key }}</strong>
                                            </td>
                                            <td><strong class="site-badge primary">{{ $value }}</strong></td>
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
@endsection

