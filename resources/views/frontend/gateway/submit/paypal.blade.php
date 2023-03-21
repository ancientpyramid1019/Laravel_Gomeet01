<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'tdevs') }}</title>
</head>

<body>
<form action="{{$data['action']}}" method="{{$data['account']}}" id="auto_submit">
    @csrf
    <input type="hidden" name="txn" value="{{$txn}}">
</form>
<script>
    'use strict';
    document.getElementById("auto_submit").submit();
</script>
</body>

</html>
