<div class="col-xl-6 col-lg-12 col-md-12 col-12">
    <div class="site-card">
        <div class="site-card-header">
            <h3 class="title">{{$fields['title']}}</h3>
        </div>
        <div class="site-card-body">
            @include('backend.setting.site_setting.include.form.__open_action')

            @foreach( $fields['elements'] as $key => $field)
                @if($field['type'] == 'file')
                    <div class="site-input-groups row">
                        <div
                            class="col-xl-4 col-lg-4 col-md-3 col-12 col-label"
                        >
                            {{ __($field['label']) }}
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-9 col-12">
                            <div class="wrap-custom-file {{ $errors->has($field['name']) ? 'has-error' : '' }}">
                                <input
                                    type="{{$field['type']}}"
                                    name="{{$field['name']}}"
                                    id="{{$field['name']}}"
                                    value="{{ oldSetting($field['name'],$section) }}"
                                    accept=".jpeg, .jpg, .png"
                                />
                                <label for="{{ __($field['name']) }}" class="file-ok"
                                       style="background-image: url( {{asset(oldSetting($field['name'],$section)) }} )">
                                    <img
                                        class="upload-icon"
                                        src="{{ asset('global/materials/upload.svg') }}"
                                        alt=""
                                    />
                                    <span>{{ __('upload') .' '.__($field['label'])}} </span>
                                </label>
                            </div>
                        </div>
                    </div>
                @elseif($field['type'] == 'dropdown')

                    <div class="site-input-groups row">
                        <label for="" class="col-sm-4 col-label">{{ __($field['label']) }}</label>
                        <div class="col-sm-8">
                            <select name="{{$field['name']}}" class="form-select" id="">

                                @if($field['name'] == 'site_currency')
                                    @foreach($siteCurrency as $key => $value)
                                        <option @if(oldSetting($field['name'],$section) == $key) selected
                                                @endif value="{{$key}}"> {{$value}}({{$key}})
                                        </option>
                                    @endforeach
                                @endif

                                @if($field['name'] == 'site_timezone')
                                    @foreach(getTimezone() as $timezone)
                                        <option @if(oldSetting($field['name'],$section) == $timezone['name']) selected
                                                @endif value="{{$timezone['name']}}"> {{$timezone['name']}}
                                        </option>
                                    @endforeach
                                @endif


                            </select>

                        </div>
                    </div>
                @else
                    <div class="site-input-groups row">
                        <label for="" class="col-sm-4 col-label">{{ __($field['label']) }}</label>
                        <div class="col-sm-8">
                            <div class="input-group joint-input">

                                <input
                                    type="{{$field['type']}}"
                                    name="{{$field['name']}}"
                                    class=" form-control {{ $errors->has($field['name']) ? 'has-error' : '' }}"
                                    value="{{ oldSetting($field['name'],$section) }}"
                                />

                                @if($field['data'] == 'double')
                                    <span class="input-group-text"> {{ setting('site_currency','global') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach


            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
</div>

