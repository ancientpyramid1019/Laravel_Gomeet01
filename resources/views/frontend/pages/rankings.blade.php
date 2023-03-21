@extends('frontend.pages.index')
@section('title')
    {{ $data['title'] }}
@endsection
@section('meta_keywords')
    {{ $data['meta_keywords'] }}
@endsection
@section('meta_description')
    {{ $data['meta_description'] }}
@endsection
@section('page-content')
    @php
        $rankings = \App\Models\Ranking::where('status',true)->get()
    @endphp
    <section class="section-style">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-12">
                    <div class="section-title text-center">
                        <h4 data-aos="fade-down" data-aos-duration="2000">{{ $data['title_small'] }}</h4>
                        <h2 data-aos="fade-down" data-aos-duration="1500">{{ $data['title_big'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="frontend-editor-data">
                        {!! $data['content'] !!}
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                @foreach($rankings as $ranking)
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="single-badge">
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
    </section>
@endsection
