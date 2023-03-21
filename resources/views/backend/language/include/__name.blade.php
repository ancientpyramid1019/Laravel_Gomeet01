<div class="table-description">
    <div class="icon">
        <i icon-name="languages"></i>
    </div>
    <div class="description">
        <strong>{{ $name }}</strong>
        @if($is_default)
            <span class="site-badge primary ms-2">{{ __('Default') }}</span>
        @endif

        <div class="date">{{$locale}}</div>
    </div>
</div>
<script>
    "use strict";
    lucide.createIcons();
</script>
