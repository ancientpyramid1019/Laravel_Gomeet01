@if( setting('gdpr_status','gdpr') == true)

    <div class="caches-privacy cookiealert" hidden>
        <div class="content">
            <p>{{ setting('gdpr_text','gdpr') }} <a href="{{ url(setting('gdpr_button_url','gdpr')) }}"
                                                    target="_blank">{{setting('gdpr_button_label','gdpr')}}</a></p>
        </div>
        <button class="site-btn blue-btn acceptcookies">{{ __('Accept All') }}</button>
    </div>

@endif
