@extends('frontend.pages.index')
@section('title')
    {{ $blog->title }}
@endsection
@section('page-content')
    <section class="section-style-2 light-blue-bg">
        <div class="container">
            <div class="row">
                <div class="col-xxl-4 col-xl-6 col-lg-12 col-md-12 col-sm-12 order-xl-2 order-lg-2 order-md-2 order-sm-2 order-xxl-1 order-1">
                    <div class="site-sidebar">
                        <div class="single-sidebar">
                            <h3>{{ $data['sidebar_widget_title'] }}</h3>
                            <ul>
                                @foreach($blogs as $id => $title)
                                    <li><a href="{{ route('blog-details',$id) }}">{{ Str::limit($title,35)   }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-8 col-xl-12 col-lg-12 col-md-12 col-sm-12 order-xl-1 order-lg-1 order-md-1 order-sm-1 order-xxl-2">
                    <div class="blog-details">
                        <img class="big-thumb" src="{{ asset($blog->cover) }}" alt=""/>
                        <div class="frontend-editor-data">
                            {!! $blog->details !!}
                        </div>
                    </div>
                    <div class="post-share-and-tag row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="social">
                                <span>Share:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('blog-details',$blog->id) }}"
                                   class="cl-facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?text=my share text&amp;url={{ route('blog-details',$blog->id) }}"
                                   class="cl-twitter"><i class="fab fa-twitter"></i></a>
                                <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ route('blog-details',$blog->id) }}"
                                   class="cl-pinterest"><i class="fab fa-linkedin"></i></a>
                                <a href="https://wa.me/?text={{ route('blog-details',$blog->id) }}"
                                   class="cl-pinterest"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
