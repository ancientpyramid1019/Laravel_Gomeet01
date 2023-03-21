@if($status == 'ongoing')

    <div>
        <strong><span id="days{{ $id }}"></span>D : <span id="hours{{ $id }}"></span>H : <span
                id="minutes{{ $id }}"></span>M : <span id="seconds{{ $id }}"></span>S</strong>
        <span class="site-badge primary-bg ms-2" id="percentage{{ $id }}"></span>
    </div>
    <div class="progress investment-timeline">
        <div class="progress-bar progress-bar-striped progress-bar-animated" id="time-progress{{ $id }}"
             role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <script>

        (function ($) {
            "use strict";
            // Countdown
            const second = 1000,
                minute = second * 60,
                hour = minute * 60,
                day = hour * 24;
            let timezone = @json(setting('site_timezone','global'));

            let countDown = new Date('{{$next_profit_time}}').getTime()
            var start = new Date('{{ $last_profit_time ?? $created_at}}').getTime()
            setInterval(function () {

                let utc_datetime_str = new Date().toLocaleString("en-US", { timeZone: timezone });
                let now = new Date(utc_datetime_str).getTime();
                let  distance = countDown - now;


                var progress = (((now - start) / (countDown - start)) * 100).toFixed(2);


                $("#time-progress{{ $id }}").css("width", progress + '%');

                $("#percentage{{ $id }}").text(progress >= 100 ? 100 + '%' : progress + '%');

                document.getElementById('days{{ $id }}').innerText = Math.floor(distance < 0 ? 0 : distance / (day)),
                    document.getElementById('hours{{ $id }}').innerText = Math.floor(distance < 0 ? 0 : (distance % (day)) / (hour)),
                    document.getElementById('minutes{{ $id }}').innerText = Math.floor(distance < 0 ? 0 : (distance % (hour)) / (minute)),
                    document.getElementById('seconds{{ $id }}').innerText = Math.floor(distance < 0 ? 0 : (distance % (minute)) / second);

            }, second)

        })(jQuery)

    </script>
@elseif($status == 'completed')
    <div class="site-badge success">{{ __('Completed') }}</div>
    <div class="progress investment-timeline">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75"
             aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
    </div>
@else
    <div class="site-badge pending">{{ __('Pending') }}</div>
@endif
