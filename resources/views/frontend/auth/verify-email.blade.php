@extends('frontend.layouts.auth')
@section('title')
    {{ __('Verify Email') }}
@endsection
@section('content')
    <section class="section-style site-auth">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-8 col-md-12">
                    <div class="auth-content">
                        <div class="logo">
                            <a href="{{ route('home')}}"><img src="{{ asset(setting('site_logo','global')) }}" alt=""/></a>
                        </div>
                        <div class="title">
                            <h2>👋 {{ __('Welcome Back!') }}</h2>
                            <p>{{ __('verify your email address by clicking on the link we just emailed to you') }}</p>
                        </div>
                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                            </div>

                        @endif
                        <div class="site-auth-form">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf

                                <button type="submit" class="site-btn grad-btn w-100">
                                    {{ __('Resend Verification Email') }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="site-btn grad-btn w-100">
                                    {{ __('Log Out') }}
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection



