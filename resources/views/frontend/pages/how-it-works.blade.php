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

    @include('frontend.home.include.__howitworks',['data' => $data ])


    <!-- section  -->
    @if(isset($data['section_id']) && $data['section_id'])

        @php
            $section = \App\Models\LandingPage::find($data['section_id'])
        @endphp

        @include('frontend.home.include.__'.$section->code,['data' => json_decode($section->data, true) ])

    @endif
    <!-- section end-->

@endsection

