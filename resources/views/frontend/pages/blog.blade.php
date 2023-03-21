@extends('frontend.pages.index')
@section('title')
    {{ $data->title }}
@endsection
@section('meta_keywords')
    {{ $data['meta_keywords'] }}
@endsection
@section('meta_description')
    {{ $data['meta_description'] }}
@endsection
@section('page-content')

    @php
        $blogs = \App\Models\Blog::latest()->paginate(6);
    @endphp

    <section class="section-style-2 light-blue-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="section-title centered">
                        <h2 data-aos="fade-down" data-aos-duration="1500">{{ $data->title_big }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($blogs as $blog)
                    <div class="col-xl-4 col-lg-4 col-sm-12">
                        <div class="single-blog" data-aos="fade-down" data-aos-duration="1000">
                            <div class="thumb">
                                <img src="{{ asset($blog->cover) }}" alt=""/>
                            </div>
                            <div class="content">
                                <div class="meta">
                                    <div class="date">{{ $blog->created_at }}</div>
                                </div>
                                <div class="title">
                                    <h3><a href="{{ route('blog-details',$blog->id) }}">{{ $blog->title }}</a></h3>
                                </div>
                                <div class="des">
                                    {!! Str::limit($blog->details,90) !!}
                                </div>
                                <div class="link">
                                    <a href="{{ route('blog-details',$blog->id) }}">{{ __('Continue Reading') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{ $blogs->links() }}

            </div>
        </div>
    </section>
@endsection
