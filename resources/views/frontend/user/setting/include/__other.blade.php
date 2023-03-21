<div class="row">

    {{-- 2 Factor Authentication --}}
    @include('frontend.user.setting.include.__two_fa')

    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        @if(setting('kyc_verification','permission'))
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('KYC') }}</h3>
                </div>
                <div class="site-card-body">

                    @if($user->kyc == \App\Enums\KYCStatus::Verified->value)
                        <div class="site-badge success">{{ __('KYC Verified') }}</div>
                        <p class="mt-3">{{ json_decode($user->kyc_credential,true)['Action Message'] ?? '' }}</p>
                    @else
                        <a href="{{ route('user.kyc') }}" class="site-btn blue-btn">{{ __('Upload KYC') }}</a>
                        <p class="mt-3">{{ json_decode($user?->kyc_credential,true)['Action Message'] ?? '' }}</p>
                    @endif
                </div>
            </div>
        @endif

        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">{{ __('Change Password') }}</h3>
            </div>
            <div class="site-card-body">
                <a href="{{ route('user.change.password') }}" class="site-btn blue-btn">{{ __('Change Password') }}</a>
            </div>
        </div>
    </div>
</div>
