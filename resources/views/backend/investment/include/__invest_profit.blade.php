@php
    $calculateInterest = ($interest*$invest_amount)/100;
    $interest = $interest_type != 'percentage' ? $interest : $calculateInterest;
@endphp

<strong>{{ $already_return_profit .' x '.$interest .' = '. ($already_return_profit*$interest).' '. $currency }}</strong>
