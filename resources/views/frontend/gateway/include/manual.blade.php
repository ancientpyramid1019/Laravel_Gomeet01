<div class="col-xl-12 col-md-12">
    <div class="frontend-editor-data">
        {!! $paymentDetails !!}
    </div>
</div>
@foreach( json_decode($credentials, true) as $key => $credential)

    @if($credential['type'] == 'file')
        <div class="col-xl-12 col-md-12">
            <div class="body-title">{{ $credential['name'] }}</div>
            <div class="wrap-custom-file">
                <input
                    type="file"
                    name="manual_data[{{$credential['name']}}]"
                    id="{{ $key }}"
                    accept=".gif, .jpg, .png"
                    @if($credential['validation'] == 'required') required @endif
                />
                <label for="{{ $key }}">
                    <img
                        class="upload-icon"
                        src="{{ asset('global/materials/upload.svg') }}"
                        alt=""
                    />
                    <span>{{ __('Select '). $credential['name'] }}</span>
                </label>
            </div>
        </div>
    @elseif($credential['type'] == 'textarea')
        <div class="col-xl-12 col-md-12">
            <label for="exampleFormControlInput1" class="form-label">{{ $credential['name'] }}</label>
            <div class="input-group">
                <textarea class="form-control-textarea" @if($credential['validation'] == 'required') required
                          @endif placeholder="Send Money Note" name="manual_data[{{$credential['name']}}]"></textarea>
            </div>
        </div>
    @else
        <div class="col-xl-12 col-md-12">
            <label for="exampleFormControlInput1" class="form-label">{{ $credential['name'] }}</label>
            <div class="input-group">
                <input type="text" name="manual_data[{{$credential['name']}}]"
                       @if($credential['validation'] == 'required') required @endif class="form-control"
                       aria-label="Amount" id="amount" aria-describedby="basic-addon1">
            </div>
        </div>
    @endif

@endforeach

