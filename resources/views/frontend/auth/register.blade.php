@extends('frontend.layouts.auth')

@section('title')
    {{ __('Register') }}
@endsection
@section('content')

    <!-- Login Section -->
    <section class="section-style site-auth">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-md-12">
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
                                    <strong>{{ __('You Entered') }} {{ $error }}</strong>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        @endif


                        <div class="site-auth-form">
                            <form method="POST" action="{{ route('register') }}" class="row">
                                @csrf
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="single-field">
                                        <label class="box-label" for="name">{{ __('First Name') }}<span
                                                class="required-field">*</span></label>
                                        <input
                                            class="box-input"
                                            type="text"
                                            placeholder="Your First Name"
                                            name="first_name"
                                            value="{{ old('first_name') }}"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="single-field">
                                        <label class="box-label" for="name">{{ __('Last Name') }}<span
                                                class="required-field">*</span></label>
                                        <input
                                            class="box-input"
                                            type="text"
                                            placeholder="Your Last Name"
                                            name="last_name"
                                            value="{{ old('last_name') }}"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="single-field">
                                        <label class="box-label" for="email">{{ __('Email Address') }}<span
                                                class="required-field">*</span></label>
                                        <input
                                            class="box-input"
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            placeholder="Enter Your Email Address"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="single-field">
                                        <label class="box-label" for="username">{{ __('User Name') }}<span
                                                class="required-field">*</span></label>
                                        <input
                                            class="box-input"
                                            type="text"
                                            placeholder="Enter Your User Name"
                                            name="username"
                                            value="{{ old('username') }}"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="single-field">
                                        <label class="box-label" for="username">{{ __('Select Country') }}<span
                                                class="required-field">*</span></label>

                                        <select name="country" id="countrySelect" class="site-nice-select">
                                            @foreach( getCountries() as $country)
                                                <option @if( $location->country_code == $country['code']) selected
                                                        @endif value="{{ $country['name'].':'.$country['dial_code'] }}">
                                                    {{ $country['name']  }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="single-field">
                                        <label class="box-label" for="username">{{ __('Phone Number') }}<span
                                                class="required-field">*</span></label>
                                        <div class="input-group joint-input"><span class="input-group-text"
                                                                                   id="dial-code">{{ getLocation()->dial_code }}</span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Phone Number"
                                                name="phone"
                                                value="{{ old('phone') }}"
                                                aria-label="Username"
                                                aria-describedby="basic-addon1"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="single-field">
                                        <label class="box-label" for="password">{{ __('Password') }}<span
                                                class="required-field">*</span></label
                                        >
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
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="single-field">
                                        <label class="box-label" for="password">{{ __('Confirm Password') }}<span
                                                class="required-field">*</span></label>
                                        <div class="password">
                                            <input
                                                class="box-input"
                                                type="password"
                                                name="password_confirmation"
                                                placeholder="Enter your password"
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                                    <div class="single-field">
                                        @if($googleReCaptcha)
                                            <div class="g-recaptcha" id="feedback-recaptcha"
                                                 data-sitekey="{{ json_decode($googleReCaptcha->data,true)['google_recaptcha_key'] }}">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                                    <div class="single-field">
                                        <input
                                            class="form-check-input check-input"
                                            type="checkbox"
                                            name="i_agree"
                                            value="yes"
                                            id="flexCheckDefault"
                                            required
                                        />
                                        <label class="form-check-label" for="flexCheckDefault">
                                            {{ __('I agree with') }}
                                            <a href="{{url('/privacy-policy')}}">{{ __('Privacy & Policy') }}</a> {{ __('and') }}
                                            <a href="{{url('/terms-and-conditions')}}">{{ __('Terms & Condition') }}</a>
                                        </label>
                                    </div>
                                </div>


                                <div class="col-xl-12">
                                    <button type="submit" class="site-btn grad-btn w-100">
                                        {{ __('Create Account') }}
                                    </button>
                                </div>
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
@section('script')
    @if($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <script>
        $('#countrySelect').on('change', function (e) {
            "use strict";
            e.preventDefault();
            var country = $(this).val();
            $('#dial-code').html(country.split(":")[1])
        })
    </script>
@endsection

