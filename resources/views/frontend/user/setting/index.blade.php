@extends('frontend.layouts.user')
@section('title')
    {{ __('Settings') }}
@endsection
@section('content')

    {{--profile settings--}}
    @include('frontend.user.setting.include.__profile')

    {{--Other settings--}}
    @include('frontend.user.setting.include.__other')

@endsection

