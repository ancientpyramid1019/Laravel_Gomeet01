@php
    $investors = \App\Models\Invest::with('schema')->latest()->take(6)->get();
    $withdraws = \App\Models\Transaction::where('type',\App\Enums\TxnType::Withdraw)->take(6)->latest()->get();
@endphp

<section class="section-style-2 light-blue-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-12">
                <div class="section-title text-center">
                    <h4 data-aos="fade-down" data-aos-duration="2000">{{ $data['title_small'] }}</h4>
                    <h2 data-aos="fade-down" data-aos-duration="1500">
                        {{ $data['title_big'] }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-lg-12">
                <div class="site-card" data-aos="fade-right" data-aos-duration="2000">
                    <div class="site-card-header">
                        <h3 class="title">{{ __('Recent Investors') }}</h3>
                    </div>
                    <div class="site-card-body">
                        <div class="site-transactions">
                            @foreach($investors as $investor)

                                @php
                                    $calculateInterest = ($investor->interest*$investor->invest_amount)/100;
                                    $interest = $investor->interest_type != 'percentage' ? $investor->interest : $calculateInterest;
                                @endphp

                                <div class="single">
                                    <div class="left">
                                        <div class="icon">
                                            <i icon-name="user-plus"></i>
                                        </div>
                                        <div class="content">
                                            <div class="title">{{ $investor->user->full_name }}</div>
                                            <div class="date">{{ $investor->created_at }}</div>
                                        </div>
                                    </div>
                                    <div class="middle">
                                        @if($investor->user->status)
                                            <div class="status site-badge badge-success">{{ __('Active') }}</div>
                                        @else
                                            <div class="status site-badge badge-pending">{{ __('DeActive') }}</div>
                                        @endif
                                    </div>
                                    <div class="right">
                                        <div class="amount">
                                            <div class="net in">
                                                +{{ $investor->already_return_profit*$interest }} {{ $currency }}</div>
                                            <div class="total">{{ $investor->invest_amount }} {{ $currency }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12">
                <div class="site-card" data-aos="fade-left" data-aos-duration="2000">
                    <div class="site-card-header">
                        <h3 class="title">{{ __('Recent Withdraws') }}</h3>
                    </div>
                    <div class="site-card-body">
                        <div class="site-transactions">
                            @foreach($withdraws as $withdraw)
                                <div class="single">
                                    <div class="left">
                                        <div class="icon">
                                            <i icon-name="arrow-up-left"></i>
                                        </div>
                                        <div class="content">
                                            <div class="title">{{ $withdraw->description }}</div>
                                            <div class="date">{{ $withdraw->created_at }}</div>
                                        </div>
                                    </div>
                                    <div class="middle">


                                        @if($withdraw->status == \App\Enums\TxnStatus::Success)
                                            <div class="status site-badge badge-success">{{ __('Success') }}</div>
                                        @elseif( $withdraw->status == \App\Enums\TxnStatus::Failed)
                                            <div class="status site-badge badge-failed">{{ __('Cancelled') }}</div>
                                        @else
                                            <div class="status site-badge badge-pending">{{ __('Pending') }}</div>
                                        @endif

                                    </div>
                                    <div class="right">
                                        <div class="amount">
                                            @if($withdraw->status == \App\Enums\TxnStatus::Success)
                                                <div class="net in">+{{ $withdraw->final_amount }} {{ $currency }}</div>
                                            @elseif($withdraw->status == \App\Enums\TxnStatus::Failed)
                                                <div class="net out">
                                                    -{{ $withdraw->final_amount }} {{ $currency }}</div>
                                            @endif

                                            <div class="total">{{ $withdraw->final_amount }} {{ $currency }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
