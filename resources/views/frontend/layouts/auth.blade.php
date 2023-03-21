<!DOCTYPE html>
<html lang="en">
@include('frontend.include.__head')
<body>
<x:notify-messages/>

@yield('content')

@include('frontend.include.__script')

</body>
</html>

