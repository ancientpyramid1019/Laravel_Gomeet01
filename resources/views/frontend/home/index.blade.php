@extends('frontend.layouts.app')
@section('title')
    {{ __('Home') }}
@endsection
@section('content')
    @foreach($homeContent as $content)
        @php
            $data = json_decode($content->data,true);
        @endphp
        @include('frontend.home.include.__'.$content->code,['data' => $data])
    @endforeach
@endsection
