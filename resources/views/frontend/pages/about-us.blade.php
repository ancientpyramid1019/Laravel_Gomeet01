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

    <section class="about-us section-style">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="about-img">
                        <img src="{{ asset($data['aboutusLeftImg']) }}" alt=""/>
                        <div class="content">{{ $data['left_img_badge'] }}</div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="about-content">
                        <div class="section-title mb-4">
                            <h4>{{ $data['title_small'] }}</h4>
                            <h2>{{ $data['title_big'] }}</h2>
                        </div>
                        <div class="content">
                            {{ $data['side_content'] }}
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 mt-4">
                    <div class="about-content">
                        <div class="content">
                            <div class="frontend-editor-data">
                                {!! $data['content'] !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
