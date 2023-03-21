<form action="{{ route('admin.settings.plugin.update',$plugin->id) }}" method="post">
    @csrf
    <h3 class="title mb-4">{{ __('Update').' '. $plugin->name }}</h3>


    @foreach(json_decode($plugin->data) as $key => $value)
        <div class="site-input-groups">
            <label for="" class="box-input-label">{{ ucwords(str_replace('_',' ',$key)) }}</label>
            <input type="text" name="data[{{ $key }}]" class="box-input mb-0" value="{{ $value }}" required=""/>
        </div>
    @endforeach


    <div class="site-input-groups">
        <label class="box-input-label" for="">{{ __('Status:') }}</label>
        <div class="switch-field">
            <input
                type="radio"
                id="plugin-status"
                name="status"
                value="1"
                @if($plugin->status) checked @endif
            />
            <label for="plugin-status">{{ __('Active') }}</label>
            <input
                type="radio"
                id="plugin-status-no"
                name="status"
                value="0"
                @if(!$plugin->status) checked @endif

            />
            <label for="plugin-status-no">{{ __('DeActive') }}</label>
        </div>
    </div>

    <div class="action-btns">
        <button type="submit" class="site-btn-sm primary-btn me-2">
            <i icon-name="check"></i>
            {{ __(' Save Changes') }}
        </button>
        <a
            href="#"
            class="site-btn-sm red-btn"
            data-bs-dismiss="modal"
            aria-label="Close"
        >
            <i icon-name="x"></i>
            {{ __('Close') }}
        </a>
    </div>
</form>
