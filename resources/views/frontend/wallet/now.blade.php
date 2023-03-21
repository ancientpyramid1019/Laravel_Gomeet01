@extends('frontend.wallet.index')
@section('wallet_exchange_content')
    <div class="progress-steps-form">
        <form action="{{ route('user.wallet-exchange-now') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-xl-4 col-md-12">
                    <label for="exampleFormControlInput1" class="form-label">{{ __('From Wallet:') }}</label>
                    <div class="input-group">
                        <select name="from_wallet" class="site-nice-select">
                            <option value="1">{{ __('Main Wallet').' ('. $user->balance.' '.$currency .')' }}</option>
                            <option selected
                                    value="2">{{ __('Profit Wallet').' ('. $user->profit_balance.' '.$currency .')' }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 col-md-12">
                    <label for="exampleFormControlInput1" class="form-label">{{ __('Enter Amount:') }}</label>
                    <div class="input-group">
                        <input type="text" name="amount" class="form-control"
                               oninput="this.value = validateDouble(this.value)" aria-label="Amount" id="amount"
                               aria-describedby="basic-addon1">
                        <span class="input-group-text" id="basic-addon1">{{ $currency }}</span>
                    </div>
                    <div class="input-info-text charge"></div>
                </div>

                <div class="col-xl-4 col-md-12">
                    <label for="exampleFormControlInput1" class="form-label">{{ __('To Wallet:') }}</label>
                    <div class="input-group">
                        <select name="to_wallet" class="site-nice-select">
                            <option selected
                                    value="1">{{ __('Main Wallet').' ('. $user->balance.' '.$currency .')' }}</option>
                            <option
                                value="2">{{ __('Profit Wallet').' ('. $user->profit_balance.' '.$currency .')' }}</option>
                        </select>
                    </div>

                </div>

            </div>


            <div class="transaction-list table-responsive">
                <div class="user-panel-title">
                    <h3>{{ __('Review Details:') }}</h3>
                </div>
                <table class="table">
                    <tbody>
                    <tr>
                        <td><strong>{{ __('Amount') }}</strong></td>
                        <td><span class="amount"></span> <span class="currency"></span></td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('Charge') }}</strong></td>
                        <td class="charge2"></td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('Total') }}</strong></td>
                        <td class="total"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="buttons">
                <button type="submit" class="site-btn blue-btn">
                    {{ __('Proceed to Exchange') }}<i class="anticon anticon-double-right"></i>
                </button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>

        "use strict"

        var currency = @json($currency);

        var charge_type = @json( setting('wallet_exchange_charge_type','fee') );
        var charge = @json( setting('wallet_exchange_charge','fee') );

        $('#amount').on('keyup',function (e) {

            var amount = $(this).val()

            $('.amount').text((Number(amount)))

            $('.currency').text(currency)

            var finalCharge = charge_type === 'percentage' ? calPercentage(amount, charge) : charge


            $('.charge2').text(finalCharge + ' ' + currency)

            $('.total').text((Number(amount) + Number(finalCharge)) + ' ' + currency)


            $('.charge').text('Charge ' + charge + ' ' + (charge_type === 'percentage' ? ' % ' : currency))
        })
    </script>
@endsection



