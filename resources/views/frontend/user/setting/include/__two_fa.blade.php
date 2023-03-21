<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
    <div class="site-card">
        <div class="site-card-header">
            <h3 class="title">{{ __('2FA Security') }}</h3>
        </div>
        <div class="site-card-body">

            @if( null != $user->google2fa_secret)

                <div class="progress-steps-form">
                    <p>{{ __('Two Factor Authentication (2FA) Strengthens Access Security By Requiring Two Methods (also Referred To As Factors) To Verify Your Identity. Two Factor Authentication Protects Against Phishing, Social Engineering And Password Brute Force Attacks And Secures Your Logins From Attackers Exploiting Weak Or Stolen Credentials.') }}</p>
                    <p>{{ __('Scane the QR code with you Google Authenticator App') }}</p>

                    @php
                        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

                        $inlineUrl = $google2fa->getQRCodeInline(
                           setting('site_title','global'),
                            $user->email,
                            $user->google2fa_secret
                        );
                    @endphp

{{--                    {!! app('pragmarx.google2fa')->getQRCodeInline(config('app.name'), $user->email, $user->google2fa_secret) !!}--}}

                    <img src="{{ $inlineUrl }}">

                    <p class="pt-2">
                        @if($user->two_fa)
                            {{ __('Enter Your Password') }}
                        @else
                            {{ __('Enter the PIN from Google Authenticator App') }}
                        @endif
                    </p>

                    <form action="{{ route('user.setting.action-2fa') }}" method="POST">
                        @csrf

                        <div class="input-group">
                            <input type="password" name="one_time_password" class="form-control">
                        </div>
                        <div class="buttons mt-4">
                            @if($user->two_fa)
                                <button type="submit" class="site-btn blue-btn" value="disable"
                                        name="status">{{ __('Disable 2FA') }}<i
                                        class="anticon anticon-double-right"></i></button>
                            @else
                                <button type="submit" class="site-btn blue-btn" value="enable"
                                        name="status">{{ __('Enable 2FA') }}<i class="anticon anticon-double-right"></i>
                                </button>
                            @endif
                        </div>

                    </form>

                </div>

            @else
                <a href="{{ route('user.setting.2fa') }}"
                   class="site-btn blue-btn">{{ __('Obtaining a Secret Key for Two-Factor Authentication') }}</a>
            @endif
        </div>
    </div>
</div>
