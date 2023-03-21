@extends('frontend.layouts.auth')
@section('title')
    {{ __('Forgot password') }}
@endsection
@section('content')
    <!-- Login Section -->
    <section class="section-style site-auth">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-8 col-md-12">
                    <div class="auth-content">
                        <div class="logo">
                            <a href="{{ route('home')}}"><img src="{{ asset(setting('site_logo','global')) }}" alt=""/></a>
                        </div>
                        <div class="title">
                            <h2> {{ $data['title'] }}</h2>
                            <p>{{ $data['bottom_text'] }}</p>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                @foreach($errors->all() as $error)
                                    <strong>{{$error}}</strong>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('status'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>{{ session('status') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        @endif


                        <div class="site-auth-form">


                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <!-- Email Address -->

                                <div class="single-field">
                                    <label class="box-label" for="email">{{ __('Email') }}</label>
                                    <input
                                        class="box-input"
                                        type="text"
                                        name="email"
                                        placeholder="Enter your email address"
                                        required
                                        value="{{ old('email') }}"
                                    />
                                </div>


                                <button type="submit" class="site-btn grad-btn w-100">
                                    {{ __('Email Password Reset Link') }}
                                </button>
                            </form>

                            <div class="singnup-text">
                                <p>{{ __('Already have an account?') }} <a
                                        href="{{ route('login') }}">{{ __('Login') }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Login Section End -->
@endsection


