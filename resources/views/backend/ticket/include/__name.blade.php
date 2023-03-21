@php
    $user = \App\Models\User::find($user_id)
@endphp

<div class="table-description">
    <div class="icon">
        <i icon-name="megaphone"></i>
    </div>
    <div class="description">
        <strong>{{ $title. ' - '. $uuid }}</strong>
        <div class="date"><a href="{{ route('admin.user.edit',$user->id) }}" class="link"> {{ $user->username }}</a>
        </div>
    </div>
</div>
<script>
    'use strict';
    lucide.createIcons();
</script>
