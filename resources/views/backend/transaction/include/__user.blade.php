@php
    $user = \App\Models\User::find($user_id);
@endphp
<a class="link" href="{{ route('admin.user.edit',$user->id) }}">{{ safe($user->username) }}</a>
