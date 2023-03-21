<section class="section-style-2 light-blue-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="section-title centered">
                    <h4 data-aos="fade-down" data-aos-duration="2000">{{ $data['blog_title_small'] }}</h4>
                    <h2 data-aos="fade-down" data-aos-duration="1500">{{ $data['blog_title_big'] }}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach(\App\Models\Blog::latest()->take(3)->get() as $blog)
                <div class="col-xl-4 col-lg-6 col-sm-12">
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
                                {!! Str::limit($blog->details,100) !!}
                            </div>
                            <div class="link">
                                <a href="{{ route('blog-details',$blog->id) }}">{{ __('Continue Reading') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
