<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'tdevs') }}</title>
</head>

<body>
<form action="{{$data['action']}}" method="{{$data['method']}}" id="auto_submit">
    @csrf

    <input type="hidden" name='v_merchant_id' value='{{$data['info']['merchant_id']}}'/>
    <input type="hidden" name='merchant_ref' value='{{$txn}}'/>
    <input type="hidden" name='memo' value='Deposit'/>

    <input type="hidden" name='cur' value='{{$data['info']['currency']}}'/>
    <input type="hidden" name='total' value='{{$data['info']['amount']}}'/>
    <input type="hidden" name='email' value='{{$data['info']['email']}}'/>

    <input type="hidden" name='notify_url' value=''/>
    <input type="hidden" name='fail_url' value='{{route('status.cancel')}}'/>
    <input type="hidden" name='success_url' value='{{$data['info']['success_url']}}'/>
</form>
<script>
    'use strict';
    document.getElementById("auto_submit").submit();
</script>
</body>

</html>
