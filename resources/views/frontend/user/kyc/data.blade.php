@foreach( json_decode($fields, true) as $key => $field)

    @if($field['type'] == 'file')
        <div class="col-xl-12 col-md-12">
            <div class="body-title">{{ $field['name'] }}</div>
            <div class="wrap-custom-file">
                <input
                    type="file"
                    name="kyc_credential[{{$field['name']}}]"
                    id="{{ $key }}"
                    accept=".gif, .jpg, .png"
                    @if($field['validation'] == 'required') required @endif
                />
                <label for="{{ $key }}">
                    <img
                        class="upload-icon"
                        src="{{ asset('global/materials/upload.svg') }}"
                        alt=""
                    />
                    <span>{{ __('Select '). $field['name'] }}</span>
                </label>
            </div>
        </div>
    @elseif($field['type'] == 'textarea')

        <div class="col-xl-12 col-md-12">
            <div class="progress-steps-form">
                <label for="exampleFormControlInput1" class="form-label">{{ $field['name'] }}</label>
                <div class="input-group">
                    <textarea class="form-control-textarea" @if($field['validation'] == 'required') required
                              @endif placeholder="Send Money Note" name="kyc_credential[{{$field['name']}}]"></textarea>
                </div>
            </div>
        </div>

    @else
        <div class="col-xl-12 col-md-12">
            <div class="progress-steps-form">
                <label for="exampleFormControlInput1" class="form-label">{{ $field['name'] }}</label>
                <div class="input-group">
                    <input type="text" name="kyc_credential[{{$field['name']}}]"
                           @if($field['validation'] == 'required') required @endif class="form-control"
                           aria-label="Amount" id="amount" aria-describedby="basic-addon1">
                </div>
            </div>
        </div>
    @endif

@endforeach

