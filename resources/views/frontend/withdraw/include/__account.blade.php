<div class="col-xl-6 col-md-12">
    <label for="exampleFormControlInput1" class="form-label">{{ __('Method Name:') }}</label>
    <div class="input-group">
        <input type="text" name="method_name" class="form-control" placeholder="eg. Withdraw Method - USD"
               value="{{ $withdrawMethod->name .'-'. $withdrawMethod->currency}}">
    </div>
</div>



@foreach( json_decode($withdrawMethod->fields, true) as $key => $field)

    @if($field['type'] == 'file')

        <input type="hidden" name="credentials[{{ $field['name']}}][type]" value="{{ $field['type'] }}">
        <input type="hidden" name="credentials[{{ $field['name']}}][validation]" value="{{ $field['validation'] }}">

        <div class="col-xl-6 col-md-12">
            <div class="body-title">{{ $field['name'] }}</div>
            <div class="wrap-custom-file">
                <input
                    type="file"
                    name="credentials[{{ $field['name']}}][value]"
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
        <input type="hidden" name="credentials[{{ $field['name']}}][type]" value="{{ $field['type'] }}">
        <input type="hidden" name="credentials[{{ $field['name']}}][validation]" value="{{ $field['validation'] }}">

        <div class="col-xl-6 col-md-12">
            <label for="exampleFormControlInput1" class="form-label">{{ $field['name'] }}</label>
            <div class="input-group">
                <textarea class="form-control-textarea" @if($field['validation'] == 'required') required
                          @endif placeholder="Send Money Note" name="credentials[{{$field['name']}}][value]"></textarea>
            </div>
        </div>

    @else
        <input type="hidden" name="credentials[{{ $field['name']}}][type]" value="{{ $field['type'] }}">
        <input type="hidden" name="credentials[{{ $field['name']}}][validation]" value="{{ $field['validation'] }}">

        <div class="col-xl-6 col-md-12">
            <label for="exampleFormControlInput1" class="form-label">{{ $field['name'] }}</label>
            <div class="input-group">
                <input type="text" name="credentials[{{ $field['name']}}][value]"
                       @if($field['validation'] == 'required') required @endif class="form-control" aria-label="Amount"
                       id="amount" aria-describedby="basic-addon1">
            </div>
        </div>
    @endif

@endforeach


