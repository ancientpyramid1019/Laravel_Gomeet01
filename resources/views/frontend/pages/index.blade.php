@extends('frontend.layouts.app')
@section('content')
    <!--page-head-->
    <section class="page-head site-overlay"
             style="background: url( {{ asset(getPageSetting('breadcrumb')) }}) no-repeat center top / cover;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 centered">
                    <h2 data-aos="fade-down" data-aos-duration="2000">@yield('title')</h2>
                </div>
            </div>
        </div>
    </section>
    <!--page-head End -->

    @yield('page-content')

@endsection
