@extends('frontend.send_money.index')
@section('send_money_content')

    <div class="progress-steps-form">
        <form action="{{ route('user.send-money.now') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <label for="exampleFormControlInput1" class="form-label">{{ __('User Email') }}</label>
                    <div class="input-group">
                        <input type="email" name="email" required class="form-control userCheck"
                               placeholder="User Email">
                    </div>
                    <div class="input-info-text notifyUser"></div>
                </div>
                <div class="col-xl-6 col-md-12">
                    <label for="exampleFormControlInput1" class="form-label">{{ __('Enter Amount') }}</label>
                    <div class="input-group">
                        <input type="text" class="form-control sendAmount" name="amount" required
                               placeholder="Enter Amount" aria-label="Amount"
                               oninput="this.value = validateDouble(this.value)" aria-describedby="basic-addon1">
                        <span class="input-group-text" id="basic-addon1">{{ $currency }}</span>
                    </div>
                    <div
                        class="input-info-text">{{ 'Minimum '. setting('min_send','fee').' '.$currency.' and Maximum '. setting('max_send','fee').' '.$currency }}</div>
                </div>
                <div class="col-xl-12 col-md-12 mt-3">
                    <label for="exampleFormControlInput1"
                           class="form-label">{{ __('Send Money Note (Optional)') }}</label>
                    <div class="input-group">
                        <textarea class="form-control-textarea" placeholder="Send Money Note" name="note"></textarea>
                    </div>
                </div>
            </div>
            <div class="transaction-list table-responsive">
                <div class="user-panel-title">
                    <h3>{{ __('Send Money Details') }}</h3>
                </div>
                <table class="table">
                    <tbody>
                    <tr>
                        <td><strong>{{ __('Payment Amount') }}</strong></td>
                        <td><span class="previewAmount"></span> {{ $currency }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('Charge') }}</strong></td>
                        <td><span class="previewCharge"></span> {{ $currency }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('User Email') }}</strong></td>
                        <td class="userEmail"></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="buttons">
                <button type="submit" class="site-btn blue-btn">
                    {{ __('Send Money') }}<i class="anticon anticon-double-right"></i>
                </button>
            </div>
        </form>

    </div>

@endsection
@section('script')

    <script>
        $('.userCheck').on('change',function (e) {
            "use strict"
            var email = $(this).val();

            $('.userEmail').text(email)

            var url = '{{ route("user.exist",":email") }}';
            url = url.replace(':email', email);
            $.get(url, function (data) {
                $('.notifyUser').text(data)
            })
        })

        $('.sendAmount').on('keyup',function (e) {
            "use strict"
            var amount = $(this).val();
            $('.previewAmount').text(amount);

            var charge = @json(setting('send_charge','fee'));
            var chargeType = @json(setting('send_charge_type','fee'));


            if (chargeType === 'percentage') {
                var finalCharge = calPercentage(amount, charge)
            } else {
                var finalCharge = charge

            }
            $('.previewCharge').text(finalCharge);
        })


    </script>
@endsection



