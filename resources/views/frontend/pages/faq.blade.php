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

    @include('frontend.home.include.__faq',['data' => $data ])

    <section class="">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-12">
                    <div class="section-title text-center">
                        <a href="{{ $data['button_url'] }}" target="{{ $data['hero_button2_target'] }}"
                           class="site-btn primary-btn"><i
                                class="anticon {{ $data['button_icon'] }}"></i>{{ $data['button_level'] }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- section  -->
    @if(isset($data->section_id) && $data->section_id)

        @php
            $section = \App\Models\LandingPage::find($data->section_id);
        @endphp

        @include('frontend.home.include.__'.$section->code,['data' => json_decode($section->data, true) ])

    @endif
    <!-- section end-->
@endsection
