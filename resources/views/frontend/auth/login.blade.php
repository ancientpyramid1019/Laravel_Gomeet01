@extends('frontend.layouts.auth')

@section('title')
    {{ __('Login') }}
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


                        <div class="site-auth-form">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="single-field">
                                    <label class="box-label" for="email">{{ __('Email Or Username') }}</label>
                                    <input
                                        class="box-input"
                                        type="text"
                                        name="email"
                                        placeholder="Enter your email address or username"
                                        required
                                    />
                                </div>
                                <div class="single-field">
                                    <label class="box-label" for="password">{{ __('Password') }}</label>
                                    <div class="password">
                                        <input
                                            class="box-input"
                                            type="password"
                                            name="password"
                                            placeholder="Enter your password"
                                            required
                                        />
                                    </div>
                                </div>

                                @if($googleReCaptcha)
                                    <div class="g-recaptcha mb-3" id="feedback-recaptcha"
                                         data-sitekey="{{ json_decode($googleReCaptcha->data,true)['google_recaptcha_key'] }}">
                                    </div>
                                @endif

                                <div class="single-field">
                                    <input
                                        class="form-check-input check-input"
                                        type="checkbox"
                                        name="remember"
                                        id="flexCheckDefault"
                                    />
                                    <label class="form-check-label" for="flexCheckDefault">
                                        {{ __('Remember me') }}
                                    </label>

                                    @if (Route::has('password.request'))
                                        <span class="forget-pass-text"><a
                                                href="{{ route('password.request') }}">{{ __('Forget Password') }}</a></span>
                                    @endif
                                </div>
                                <button type="submit" class="site-btn grad-btn w-100">
                                    {{ __('Account Login') }}
                                </button>
                            </form>
                            <div class="singnup-text">
                                <p>
                                    {{ __("Don't have an account?") }}
                                    <a href="{{route('register')}}">{{ __('Signup for free') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Login Section End -->
@endsection
@section('script')
    @if($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection
