@if( $status == 'open')
    <div class="site-badge pending">{{ __('Open') }}</div>
@elseif($status == 'closed')
    <div class="site-badge success ">{{ __('Completed') }}</div>
@endif
