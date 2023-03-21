@extends('frontend.layouts.user')
@section('title')
    {{ __('KYC') }}
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('KYC') }}</h3>
                </div>

                <div class="site-card-body">
                    @if($user->kyc == \App\Enums\KYCStatus::Pending->value)
                        <div class="site-badge warnning"> {{ __('Your Kyc Is Pending') }}</div>
                    @elseif($user->kyc == \App\Enums\KYCStatus::Verified->value)
                        <div class="site-badge success"> {{ __('Your Kyc Is Verified') }} </div>
                    @else
                        <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="col-xl-12 col-md-12">
                                <div class="progress-steps-form">
                                    <label for="exampleFormControlInput1"
                                           class="form-label">{{ __('Verification Type') }}</label>
                                    <div class="input-group">
                                        <select name="kyc_id" id="kycTypeSelect" class="site-nice-select" required>
                                            <option selected disabled>----</option>
                                            @foreach($kycs as $kyc)
                                                <option value="{{ $kyc->id }}">{{$kyc->name}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="col-xl-12 col-md-12">
                                <div class="row kycData">
                                </div>
                            </div>

                            <button type="submit" class="site-btn blue-btn mt-3">{{ __('Submit Now') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#kycTypeSelect").on('change',function (e) {
            "use strict"
            e.preventDefault();

            $('.kycData').empty();

            var id = $(this).val();

            var url = '{{ route("user.kyc.data",":id") }}';
            url = url.replace(':id', id);
            $.get(url, function (data) {

                console.log(data);
                $('.kycData').append(data)
                imagePreview()

            });


        });
    </script>
@endsection
