@extends('frontend.layouts.user')
@section('title')
    {{ __('All The Badges') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('All The Badges') }}</h3>
                </div>
                <div class="site-card-body">
                    <div class="row justify-content-center">
                        @foreach($rankings as $ranking)
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="single-badge @if(!in_array($ranking->id,$alreadyRank)) locked @endif">
                                    <div class="badge">
                                        <div class="img"><img src="{{ asset($ranking->icon) }}" alt=""></div>
                                    </div>
                                    <div class="content">
                                        <h3 class="title">{{ $ranking->ranking_name }}</h3>
                                        <p class="description">{{ $ranking->description }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

