<tr>
    <td><strong>{{ __('Withdraw Fee') }}</strong></td>
    <td><span class="withdrawFee">{{ $charge }}</span> {{ $currency }}</td>
</tr>
<tr>
    <td><strong>{{ __('Withdraw Account') }}</strong></td>
    <td>{{ $name }}</td>
</tr>

@foreach($credentials as $name => $data)
    <tr>
        <td><strong>{{$name}}</strong></td>
        <td>

            @if($data['type'] == 'file')
                <img src="{{ asset( $data['value']) }}" alt="">
            @else
                {{ $data['value'] }}
            @endif
        </td>
    </tr>
@endforeach

