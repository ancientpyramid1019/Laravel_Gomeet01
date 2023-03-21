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
    <section class="section-style-2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="section-title centered">
                        <h4 data-aos="fade-down" data-aos-duration="2000">{{ $data['title_small'] }}</h4>
                        <h2 data-aos="fade-down" data-aos-duration="1500">{{ $data['title_big'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-8 col-12">
                    <div class="site-form">
                        <form action="{{ route('mail-send') }}" method="post" data-aos="fade-down"
                              data-aos-duration="2000">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-sm-12">
                                    <input type="text" placeholder="Name" name="name" required>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-sm-12">
                                    <input type="email" placeholder="Email" name="email" required>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-sm-12">
                                    <input type="text" placeholder="Subject" name="subject" required>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-sm-12">
                                    <textarea name="msg" rows="4" placeholder="Your message"></textarea>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-sm-12">
                                    <button type="submit" class="site-btn primary-btn">{{ __('Send message') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
