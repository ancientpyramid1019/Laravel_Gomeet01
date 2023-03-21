<h3 class="title mb-4">
    {{ __('KYC Details') }}
</h3>

<ul class="list-group mb-4">

    @foreach( $kycCredential as $key => $value)
        <li class="list-group-item">
            {{ $key }}: @if( file_exists('assets/'.$value))
                <img src="{{ asset($value) }}" alt=""/>
            @else
                <strong>{{ $value }}</strong>
            @endif
        </li>
    @endforeach
</ul>

@if($kycStatus !== \App\Enums\KYCStatus::Verified->value)
    <form action="{{ route('admin.kyc.action.now') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{ $id }}">


        <div class="site-input-groups">
            <label for="" class="box-input-label">{{ __('Details Message(Optional)') }}</label>
            <textarea name="message" class="form-textarea mb-0" placeholder="Details Message"></textarea>
        </div>

        <div class="action-btns">
            <button type="submit" name="status" value="1" class="site-btn-sm primary-btn me-2">
                <i icon-name="check"></i>
                {{ __('Approve') }}
            </button>
            @if($kycStatus !== \App\Enums\KYCStatus::Failed->value)
                <button type="submit" name="status" value="3" class="site-btn-sm red-btn">
                    <i icon-name="x"></i>
                    {{ __('Reject') }}
                </button>
            @endif
        </div>
    </form>
    <script>
      'use strict';
      lucide.createIcons();
    </script>
@endif
