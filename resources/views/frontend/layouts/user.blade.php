<!DOCTYPE html>
<html lang="en">
@include('frontend.include.__head')
{{-- <body class="{{ session()->get('site-color-mode') ?? 'dark-theme' }}"> --}}
<body class="">
<x:notify-messages/>
<!--Full Layout-->
<div class="panel-layout">
    <!--Header-->
    @include('frontend.include.__user_header')
    <!--/Header-->

    <!--Side Nav-->
    @include('frontend.include.__user_side_nav')
    <!--/Side Nav-->

    <div class="page-container">
        <div class="main-content">
            <div class="section-gap">
                <div class="container-fluid">
                    @if(setting('kyc_verification','permission'))
                        {{--Kyc Info --}}
                        @include('frontend.user.include.__kyc_info')
                    @endif
                    <!--Page Content-->
                    @yield('content')
                    <!--Page Content-->
                </div>
            </div>
        </div>
    </div>


    <!-- Automatic Popup -->
    @if(Session::get('signup_bonus'))
        @include('frontend.user.include.__signup_bonus')
    @endif

    <!-- /Automatic Popup End -->
</div>
<!--/Full Layout-->

@include('frontend.include.__script')

</body>
</html>

