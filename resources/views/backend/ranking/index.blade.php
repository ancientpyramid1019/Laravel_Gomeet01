@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Rankings') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('User Rankings') }}</h2>
                            @can('ranking-create')
                                <a href="" class="title-btn" type="button" data-bs-toggle="modal"
                                   data-bs-target="#addNewRanking">
                                    <i icon-name="plus-circle"></i>{{ __('Add New') }}</a>
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
                        <div class="site-card-body">
                            <div class="site-table table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('Ranking') }}</th>
                                        <th scope="col">{{ __('Ranking Icon') }}</th>
                                        <th scope="col">{{ __('Ranking Name') }}</th>
                                        <th scope="col">{{ __('Minimum Earning') }}</th>
                                        <th scope="col">{{ __('Bonus') }}</th>
                                        <th scope="col">{{ __('Description') }}</th>
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($rankings as $ranking)
                                        <tr>
                                            <td><strong>{{$ranking->ranking}}</strong></td>
                                            <td>
                                                <img class="avatar" src="{{ asset($ranking->icon) }}" alt="">
                                            </td>
                                            <td>
                                                <strong>{{ $ranking->ranking_name }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $ranking->minimum_earnings.' '.$currency }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $ranking->bonus.' '.$currency }}</strong>
                                            </td>
                                            <td>{{ $ranking->description }}</td>
                                            <td>
                                                @if($ranking->status)
                                                    <div class="site-badge success">{{ __('Active') }}</div>
                                                @else
                                                    <div class="site-badge pending">{{ __('Disabled') }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                @can('ranking-edit')
                                                    <button class="round-icon-btn primary-btn editRanking" type="button"
                                                            data-ranking="{{ json_encode($ranking) }}">
                                                        <i icon-name="edit-3"></i>
                                                    </button>
                                                @endcan
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


        <!-- Modal for Add New Ranking -->
        @can('ranking-create')
            @include('backend.ranking.include.__add_new')
        @endcan
        <!-- Modal for Add New Ranking-->

        <!-- Modal for Edit Ranking -->
        @can('ranking-edit')
            @include('backend.ranking.include.__edit')
        @endcan
        <!-- Modal for Edit Ranking-->

    </div>
@endsection
@section('script')
    <script>
        $('.editRanking').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var ranking = $(this).data('ranking');

            var url = '{{ route("admin.ranking.update", ":id") }}';
            url = url.replace(':id', ranking.id);
            $('#rankingEditForm').attr('action', url)
            $('.ranking').val(ranking.ranking);
            $('.ranking-name').val(ranking.ranking_name);
            $('.minimum-earnings').val(ranking.minimum_earnings);
            $('.bonus').val(ranking.bonus);
            $('.description').val(ranking.description);
            imagePreviewAdd(ranking.icon);

            if (ranking.status) {
                $('#disableStatus').attr('checked', false);
                $('#activeStatus').attr('checked', true);
            } else {
                $('#activeStatus').attr('checked', false);
                $('#disableStatus').attr('checked', true);
            }

            $('#editRanking').modal('show');
        });
    </script>
@endsection
