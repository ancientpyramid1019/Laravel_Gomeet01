<div class="col-xl-6 col-lg-12 col-md-12 col-12">
    <div class="site-card">
        <div class="site-card-header">
            <h3 class="title">{{ __('Permission Settings') }}</h3>
        </div>
        <div class="site-card-body">
            @include('backend.setting.site_setting.include.form.__open_action')

            @foreach($fields['elements'] as $key => $field)
                <div class="site-input-groups row">
                    <div class="col-sm-4 col-label pt-0">{{ __($field['label']) }}</div>

                    <div class="col-sm-8">
                        <div class="form-switch ps-0">
                            <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                            <div class="switch-field">
                                <input
                                    type="radio"
                                    id="active-{{$key}}"
                                    name="{{$field['name']}}"
                                    value="1"
                                    @if(oldSetting($field['name'],$section)) checked @endif
                                />
                                <label for="active-{{$key}}">{{ __('Enable') }}</label>
                                <input
                                    type="radio"
                                    id="disable-{{$key}}"
                                    name="{{$field['name']}}"
                                    value="0"
                                    @if(!oldSetting($field['name'],$section)) checked @endif
                                />
                                <label for="disable-{{$key}}">{{ __('Disabled') }}</label>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
</div>
