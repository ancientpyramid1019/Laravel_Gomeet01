<div class="row user-cards">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-inbox"></i></div>
            <div class="content">
                <h4><span class="count">{{ $dataCount['total_transaction'] }}</span></h4>
                <p>{{ __('All Transactions') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-file-add"></i></div>
            <div class="content">
                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['total_deposit'] }}</span></h4>
                <p>{{ __('Total Deposit') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-check-square"></i></div>
            <div class="content">
                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['total_investment'] }}</span></h4>
                <p>{{ __('Total Investment') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-credit-card"></i></div>
            <div class="content">
                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['total_profit'] }}</span></h4>
                <p>{{ __('Total Profit') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-arrow-right"></i></div>
            <div class="content">
                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['total_transfer'] }}</span></h4>
                <p>{{ __('Total Transfer ') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-money-collect"></i></div>
            <div class="content">
                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['total_withdraw'] }}</span></h4>
                <p>{{ __('Total Withdraw') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-gift"></i></div>
            <div class="content">
                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['total_referral_profit'] }}</span>
                </h4>
                <p>{{ __('Referral Bonus') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-account-book"></i></div>
            <div class="content">
                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['deposit_bonus'] }}</span></h4>
                <p>{{ __('Deposit Bonus') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-gold"></i></div>
            <div class="content">
                <h4><b>{{ $currencySymbol }}</b><span class="count">{{ $dataCount['investment_bonus'] }}</span></h4>
                <p>{{ __('Investment Bonus') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-inbox"></i></div>
            <div class="content">
                <h4 class="count">{{ $dataCount['total_referral'] }}</h4>
                <p>{{ __('Total Referral') }}</p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-radar-chart"></i></div>
            <div class="content">
                <h4 class="count">{{ $dataCount['rank_achieved'] }}</h4>
                <p>{{ __('Rank Achieved') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-question"></i></div>
            <div class="content">
                <h4 class="count">{{ $dataCount['total_ticket'] }}</h4>
                <p>{{ __('Total Ticket') }}</p>
            </div>
        </div>
    </div>
</div>
