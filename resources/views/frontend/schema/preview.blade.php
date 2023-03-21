@extends('frontend.layouts.user')
@section('title')
    {{ __('Schema Preview') }}
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Review and Confirm Investment') }}</h3>
                </div>
                <div class="site-card-body">
                    <form action="{{route('user.invest-now')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="progress-steps-form">
                            <div class="transaction-list table-responsive">
                                <table class="table preview-table">
                                    <tbody>
                                    <tr>
                                        <td><strong>{{ __('Select Schema:') }}</strong></td>
                                        <td>
                                            <div class="input-group mb-0">
                                                <select class="site-nice-select" aria-label="Default select example"
                                                        id="select-schema" name="schema_id" required>
                                                    @foreach($schemas as $plan)
                                                        <option value="{{$plan->id}}"
                                                                @if($plan->id == $schema->id ) selected @endif>{{$plan->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><strong>{{ __('Amount:') }}</strong></td>
                                        <td id="amount">
                                            {{ $schema->type == 'range' ? 'Minimum ' . $schema->min_amount .' '.$currency. ' - ' . 'Maximum ' . $schema->max_amount.' '.$currency :  $schema->fixed_amount.' '.$currency }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><strong>{{ __('Enter Amount:') }}</strong></td>
                                        <td>
                                            <div class="input-group mb-0">
                                                <input type="text" class="form-control"
                                                       @if($schema->type == 'fixed') value="{{ $schema->fixed_amount }}"
                                                       readonly @endif placeholder="Enter Amount"
                                                       oninput="this.value = validateDouble(this.value)"
                                                       aria-label="Amount" name="invest_amount" id="enter-amount"
                                                       aria-describedby="basic-addon1" required>
                                                <span class="input-group-text" id="basic-addon1">{{ $currency }}</span>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><strong>{{ __('Select Wallet:') }}</strong></td>
                                        <td>
                                            <div class="input-group mb-0">
                                                <select class="site-nice-select" aria-label="Default select example"
                                                        name="wallet" required id="selectWallet">
                                                    <option
                                                        value="main">{{ __('Main Wallet ( ') . $user->balance.' '. $currency }}
                                                        )
                                                    </option>
                                                    <option
                                                        value="profit">{{ __('Profit Wallet ( ') . $user->profit_balance.' '. $currency }}
                                                        )
                                                    </option>
                                                    <option value="gateway">{{ __('Direct Gateway') }}</option>
                                                </select>
                                            </div>

                                        </td>
                                    </tr>

                                    <tr class="gatewaySelect">

                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <div class="row manual-row"></div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><strong>{{ __('Return of Interest:') }}</strong></td>
                                        <td id="return-interest">{{ ($schema->interest_type == 'percentage' ? $schema->return_interest.'%' : $schema->return_interest.' '.$currency ) .' ('.$schema->schedule->name .')' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Number of Period:') }}</strong></td>
                                        <td id="number-period">{{( $schema->return_type == 'period' ? $schema->number_of_period : 'Unlimited').($schema->number_of_period == 1 ? ' Time' : ' Times' )  }} </td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Capital Back:') }}</strong></td>
                                        <td id="capital_back">{{ $schema->capital_back ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Total Investment Amount:') }}</strong></td>
                                        <td><span
                                                id="total-amount"> {{ $schema->fixed_amount ?? '' }}</span> {{ $currency }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="button">
                                <button type="submit" class="site-btn primary-btn me-3">
                                    <i class="anticon anticon-check"></i>{{ __('Invest Now') }}
                                </button>
                                <a href="{{route('user.schema')}}" class="site-btn black-btn">
                                    <i class="anticon anticon-stop"></i>{{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#select-schema").on('change',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).val();

            var invest_amount = $("#enter-amount");
            invest_amount.val('');
            invest_amount.attr('readonly', false);

            var url = '{{ route("user.schema.select", ":id") }}';
            url = url.replace(':id', id);

            $.ajax({
                url: url, success: function (result) {
                    $('#amount').html(result.amount_range);
                    $('#return-interest').html(result.return_interest);
                    $('#number-period').html(result.number_period);
                    $('#capital_back').html(result.capital_back);

                    if (result.invest_amount > 0) {
                        invest_amount.val(result.invest_amount);
                        invest_amount.attr('readonly', true);
                    }

                }
            });
        });
        $("#enter-amount").on('keyup',function (e) {
            "use strict";
            e.preventDefault();
            var amount = $(this).val();
            $("#total-amount").html(amount);
        })

        $("#selectWallet").on('change',function (e) {
            "use strict";
            $('.gatewaySelect').empty();
            $('.manual-row').empty();
            var wallet = $(this).val();
            if (wallet === 'gateway') {
                $.get('{{ route('gateway.list') }}', function (data) {
                    $('.gatewaySelect').append(data)
                    $('select').niceSelect();

                });
            }

        })
        $('body').on('change', '#gatewaySelect', function (e) {
            "use strict"
            e.preventDefault();

            $('.manual-row').empty();

            var code = $(this).val()

            var url = '{{ route("user.deposit.gateway",":code") }}';
            url = url.replace(':code', code);
            $.get(url, function (data) {

                if (data.credentials !== undefined) {

                    console.log(data.credentials);

                    $('.manual-row').append(data.credentials)
                    imagePreview()
                }


            });

            $('#amount').on('keyup',function (e) {
                "use strict"
                var amount = $(this).val()
                $('.amount').text((Number(amount)))
                $('.currency').text(currency)
                var charge = globalData.charge_type === 'percentage' ? calPercentage(amount, globalData.charge) : globalData.charge
                $('.charge2').text(charge + ' ' + currency)

                $('.total').text((Number(amount) + Number(charge)) + ' ' + currency)
            })


        });
    </script>
@endsection
