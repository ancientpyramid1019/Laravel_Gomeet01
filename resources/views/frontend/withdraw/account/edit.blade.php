@extends('frontend.layouts.user')
@section('title')
    {{ __('Withdraw Account Edit') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Edit Withdraw Account') }}</h3>
                    <div class="card-header-links">
                        <a href="{{ route('user.withdraw.account.index') }}"
                           class="card-header-link">{{ __('Withdraw Account') }}</a>
                    </div>
                </div>
                <div class="site-card-body">
                    <div class="progress-steps-form">
                        <form action="{{ route('user.withdraw.account.update',$withdrawAccount->id) }}" method="post"
                              enctype="multipart/form-data">
                            @method('PUT')
                            @csrf

                            <input type="hidden" name="withdraw_method_id" value="{{$withdrawAccount->withdraw_method_id}}">
                            <div class="row selectMethodRow">
                                <div class="col-xl-6 col-md-12">
                                    <label for="exampleFormControlInput1"
                                           class="form-label">{{ __('Method Name:') }}</label>
                                    <div class="input-group">
                                        <input type="text" name="method_name" class="form-control"
                                               placeholder="eg. Withdraw Method - USD"
                                               value="{{ $withdrawAccount->method_name }}">
                                    </div>
                                </div>


                                @foreach( json_decode($withdrawAccount->credentials, true) as $key => $field)

                                    @if($field['type'] == 'file')

                                        <input type="hidden" name="credentials[{{ $key }}][type]"
                                               value="{{ $field['type'] }}">
                                        <input type="hidden" name="credentials[{{ $key }}][validation]"
                                               value="{{ $field['validation'] }}">

                                        <div class="col-xl-6 col-md-12">
                                            <div class="body-title">{{ $key }}</div>
                                            <div class="wrap-custom-file">
                                                <input
                                                    type="file"
                                                    name="credentials[{{ $key}}][value]"
                                                    id="{{ $key }}"
                                                    accept=".gif, .jpg, .png"
                                                    @if($field['value'] == "" && $field['validation'] == 'required') required @endif
                                                />
                                                <label for="{{ $key }}" class="file-ok"
                                                       style="background-image: url({{ asset( $field['value'] ) }})">
                                                    <img
                                                        class="upload-icon"
                                                        src="{{ asset('global/materials/upload.svg') }}"
                                                        alt=""
                                                    />
                                                    <span>{{ __('Update Icon') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    @elseif($field['type'] == 'textarea')
                                        <input type="hidden" name="credentials[{{ $key}}][type]"
                                               value="{{ $field['type'] }}">
                                        <input type="hidden" name="credentials[{{ $key}}][validation]"
                                               value="{{ $field['validation'] }}">

                                        <div class="col-xl-6 col-md-12">
                                            <label for="exampleFormControlInput1" class="form-label">{{ $key }}</label>
                                            <div class="input-group">
                                                <textarea class="form-control-textarea"
                                                          @if($field['validation'] == 'required') required
                                                          @endif placeholder="Send Money Note"
                                                          name="credentials[{{$key}}][value]">{{$field['value']}}</textarea>
                                            </div>
                                        </div>

                                    @else
                                        <input type="hidden" name="credentials[{{ $key}}][type]"
                                               value="{{ $field['type'] }}">
                                        <input type="hidden" name="credentials[{{ $key}}][validation]"
                                               value="{{ $field['validation'] }}">

                                        <div class="col-xl-6 col-md-12">
                                            <label for="exampleFormControlInput1" class="form-label">{{ $key }}</label>
                                            <div class="input-group">
                                                <input type="text" name="credentials[{{ $key}}][value]"
                                                       value="{{ $field['value'] }}"
                                                       @if($field['validation'] == 'required') required
                                                       @endif class="form-control" aria-label="Amount" id="amount"
                                                       aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                    @endif

                                @endforeach


                            </div>
                            <div class="buttons">
                                <button type="submit" class="site-btn blue-btn">
                                    {{ __('Update Withdraw Account') }}<i class="anticon anticon-double-right"></i>
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
