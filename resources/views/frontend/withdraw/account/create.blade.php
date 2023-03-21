@extends('frontend.layouts.user')
@section('title')
    {{ __('Withdraw Account Create') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Add New Withdraw Account') }}</h3>
                    <div class="card-header-links">
                        <a href="{{ route('user.withdraw.account.index') }}"
                           class="card-header-link">{{ __('Withdraw Account') }}</a>
                    </div>
                </div>
                <div class="site-card-body">
                    <div class="progress-steps-form">
                        <form action="{{ route('user.withdraw.account.store') }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row selectMethodRow">
                                <div class="col-xl-6 col-md-12 selectMethodCol">
                                    <label for="exampleFormControlInput1"
                                           class="form-label">{{ __('Choice Method:') }}</label>
                                    <div class="input-group">
                                        <select name="withdraw_method_id" id="selectMethod"
                                                class="site-nice-select">
                                            <option selected>{{ __('Select Method') }}</option>
                                            @foreach($withdrawMethods as $raw)
                                                <option value="{{ $raw->id }}">{{ $raw->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                            </div>
                            <div class="buttons">
                                <button type="submit" class="site-btn blue-btn">
                                    {{ __('Add New Withdraw Account') }}<i
                                        class="anticon anticon-double-right"></i>
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
        $("#selectMethod").on('change',function (e) {
            "use strict"
            e.preventDefault();

            //$('.manual-row').empty();
            $('.selectMethodRow').children().not(':first').remove();

            var id = $(this).val()

            var url = '{{ route("user.withdraw.method",":id") }}';
            url = url.replace(':id', id);
            $.get(url, function (data) {
                $(data).insertAfter(".selectMethodCol");
                imagePreview()
            })
        })
    </script>
@endsection
