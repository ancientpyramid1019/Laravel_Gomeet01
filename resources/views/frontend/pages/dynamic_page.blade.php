@extends('frontend.pages.index')
@section('title')
    {{ $title }}
@endsection
@section('meta_keywords')
    {{ $data['meta_keywords'] }}
@endsection
@section('meta_description')
    {{ $data['meta_description'] }}
@endsection
@section('page-content')

    <!-- page content -->
    <section class="how-it-works section-style-3">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="frontend-editor-data">
                        {!! $data->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- page content end -->

    <!-- section  -->
    @if(isset($data->section_id) && $data->section_id)

        @php
            $section = \App\Models\LandingPage::find($data->section_id)
        @endphp

        @include('frontend.home.include.__'.$section->code,['data' => json_decode($section->data, true) ])

    @endif
    <!-- section end-->

@endsection
