@extends('frontend.send_money.index')
@section('send_money_content')
    <div class="progress-steps-form">
        <div class="transaction-status centered">
            <div class="icon success">
                <i class="anticon anticon-check"></i>
            </div>
            <h2>{{ $notify['title']}}</h2>
            <p>{{ $notify['p']}}</p>
            <p>{{ $notify['strong'] }}</p>
            <a href="{{ $notify['action'] }}" class="site-btn">
                <i class="anticon anticon-plus"></i>{{ $notify['a'] }}
            </a>
        </div>
    </div>
@endsection
