@extends('frontend.layouts.user')
@section('title')
    {{ __('Withdraw Now') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Withdraw Money') }}</h3>
                    <div class="card-header-links">
                        <a href="{{ route('user.withdraw.account.index') }}"
                           class="card-header-link">{{ __('Withdraw Account') }}</a>
                    </div>
                </div>
                <div class="site-card-body">
                    <div class="progress-steps-form">
                        <form action="{{ route('user.withdraw.now') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-xl-6 col-md-12 mb-3">
                                    <label for="exampleFormControlInput1"
                                           class="form-label">{{ __('Withdraw Account') }}</label>
                                    <div class="input-group">
                                        <select name="withdraw_account" id="withdrawAccountId" class="site-nice-select">
                                            <option selected disabled>{{ __('Withdraw Method') }}</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->method_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-info-text processing-time"></div>
                                </div>
                                <div class="col-xl-6 col-md-12">
                                    <label for="exampleFormControlInput1" class="form-label">{{ __('Amount') }}</label>
                                    <div class="input-group">
                                        <input type="text" name="amount"
                                               oninput="this.value = validateDouble(this.value)"
                                               class="form-control withdrawAmount" placeholder="Enter Amount">
                                    </div>
                                    <div class="input-info-text withdrawAmountRange"></div>
                                </div>
                            </div>
                            <div class="transaction-list table-responsive">
                                <div class="user-panel-title">
                                    <h3>{{ __('Withdraw Details') }}</h3>
                                </div>
                                <table class="table">
                                    <tbody class="selectDetailsTbody">
                                    <tr class="detailsCol">
                                        <td><strong>{{ __('Withdraw Amount') }}</strong></td>
                                        <td><span class="withdrawAmount"></span> {{$currency}}</td>
                                    </tr>


                                    </tbody>
                                </table>
                            </div>

                            <div class="buttons">
                                <button type="submit" class="site-btn blue-btn">
                                    {{ __('Withdraw Money') }}<i class="anticon anticon-double-right"></i>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        "use strict";
        var info = [];
        $("#withdrawAccountId").on('change',function (e) {
            e.preventDefault();

            $('.selectDetailsTbody').children().not(':first', ':second').remove();
            var accountId = $(this).val()
            var amount = $('.withdrawAmount').val();

            if (!isNaN(accountId)) {
                var url = '{{ route("user.withdraw.details",['accountId' => ':accountId', 'amount' => ':amount']) }}';
                url = url.replace(':accountId', accountId,);
                url = url.replace(':amount', amount);

                $.get(url, function (data) {
                    $(data.html).insertAfter(".detailsCol");
                    info = data.info;
                    $('.withdrawAmountRange').text(info.range)
                    $('.processing-time').text(info.processing_time)
                })
            }


        })

        $(".withdrawAmount").on('keyup',function (e) {
            "use strict"
            e.preventDefault();
            var amount = $(this).val()
            var charge = info.charge_type === 'percentage' ? calPercentage(amount, info.charge) : info.charge
            $('.withdrawAmount').text(amount)
            $('.withdrawFee').text(charge)
            $('.processing-time').text(info.processing_time)
            $('.withdrawAmountRange').text(info.range)

        })
    </script>
@endsection
