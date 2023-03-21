@extends('backend.auth.index')
@section('title')
    {{ __('Login') }}
@endsection
@section('auth-content')
    <div class="login">
        <div
            class="side-img primary-overlay"
            style="
            background: url({{ asset(setting('login_bg','global')) }}) no-repeat center center;">
            <div class="title">
                <h3>{{ __('Admin Login') }}</h3>
            </div>
        </div>
        <div class="login-content">
            <div class="logo">
                <a href="{{ route('home') }}"><img src="{{asset(setting('site_logo','global') )}}"
                                                   alt="{{asset(setting('site_title','global') )}}"/></a>
            </div>
            <div class="auth-body">

                <form action="{{ route('admin.login') }}" method="post">
                    @csrf
                    <div class="single-box">
                        <label for="" class="box-label">{{ __('Admin Email') }}</label>
                        <input
                            type="email"
                            name="email"
                            class="box-input"
                            placeholder="Admin Email"
                            required
                        />
                    </div>
                    <div class="single-box">
                        <label for="" class="box-label">{{ __('Password') }}</label>
                        <input
                            type="password"
                            name="password"
                            class="box-input"
                            placeholder="Password"
                            required
                        />
                    </div>
                    <div class="single-box">
                        <button class="site-btn primary-btn" type="submit">{{ __('Admin Login') }}</button>
                        <a href="{{route('admin.forget.password.now')}}"
                           class="link mt-2">{{ __('Forget Password?') }}</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
