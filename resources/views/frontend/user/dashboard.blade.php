@extends('frontend.layouts.user')
@section('title')
    {{ __('Dashboard') }}
@endsection
@section('content')

    {{--Referral and Ranking --}}
    @include('frontend.user.include.__referral_ranking')

    {{-- User Card--}}
    @include('frontend.user.include.__user_card')

    {{--Recent Transactions--}}
    @include('frontend.user.include.__recent_transaction')

@endsection
@section('script')
    <script>
        function copyRef() {
            /* Get the text field */
            var copyApi = document.getElementById("refLink");
            /* Select the text field */
            copyApi.select();
            copyApi.setSelectionRange(0, 999999999); /* For mobile devices */
            /* Copy the text inside the text field */
            document.execCommand('copy');
            $('#copy').text($('#copied').val())

        }
    </script>
@endsection
