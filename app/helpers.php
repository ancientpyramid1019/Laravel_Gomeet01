<?php
//error log store function
use App\Models\Gateway;
use Carbon\Carbon;


if (!function_exists('isActive')) {
    function isActive($route)
    {

        if (is_array($route)) {
            foreach ($route as $value) {
                if (Request::routeIs($value)) return 'show';
            }
        }
        if (Request::routeIs($route)) return 'active';

    }
}


if (!function_exists('setting')) {
    function setting($key, $section = null, $default = null)
    {
        if (is_null($key)) {
            return new \App\Models\Setting();
        }


        if (is_array($key)) {

            return \App\Models\Setting::set($key[0], $key[1]);
        }

        $value = \App\Models\Setting::get($key, $section, $default);

        return is_null($value) ? value($default) : $value;
    }
}


if (!function_exists('oldSetting')) {

    function oldSetting($field, $section)
    {
        return old($field, setting($field, $section));
    }
}

if (!function_exists('getPageSetting')) {

    function getPageSetting($key)
    {
        return \App\Models\PageSetting::where('key', $key)->first()->value;
    }
}

if (!function_exists('curl_get_file_contents')) {

    function curl_get_file_contents($URL)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) return $contents;
        else return FALSE;
    }
}


if (!function_exists('getCountries')) {

    function getCountries()
    {
        return json_decode(file_get_contents(resource_path() . '/json/CountryCodes.json'), true);
    }
}

if (!function_exists('getTimezone')) {

    function getTimezone()
    {
        $timeZones = json_decode(file_get_contents(resource_path() . '/json/timeZone.json'), true);
        return array_values(Arr::sort($timeZones, function ($value) {
            return $value['name'];
        }));
    }
}


if (!function_exists('getIpAddress')) {
    function getIpAddress()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}


if (!function_exists('getLocation')) {
    function getLocation()
    {
        $clientIp = request()->ip();
        $ip = $clientIp == '127.0.0.1' ? '103.77.188.202' : $clientIp;

        $location = json_decode(curl_get_file_contents('http://ip-api.com/json/'.$ip),true);


        $currentCountry = collect(getCountries())->first(function ($value, $key) use ($location) {
            return $value['code'] == $location['countryCode'];
        });

        $location = [
            'country_code' => $currentCountry['code'],
            'name' => $currentCountry['name'],
            'dial_code' => $currentCountry['dial_code'],
            'ip' => $location['query'],
        ];

        return new \Illuminate\Support\Fluent($location);
    }
}


if (!function_exists('carbonInstance')) {
    function carbonInstance($dataTime): Carbon
    {
        return Carbon::create($dataTime->toString());
    }
}

if (!function_exists('gateway_info')) {
    function gateway_info($code)
    {
        $info = Gateway::where('gateway_code', $code)->first();
        return json_decode($info->credentials);
    }
}

if (!function_exists('plugin_active')) {
    function plugin_active($name)
    {
        return \App\Models\Plugin::where('name', $name)->where('status', true)->first();
    }
}

if (!function_exists('br2nl')) {
    function br2nl($input)
    {
        return preg_replace('/<br\\s*?\/??>/i', '', $input);
    }
}

if (!function_exists('safe')) {
    function safe($input)
    {

        if (!config('app.demo')){
            return $input;
        }

        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {


            $emailParts = explode("@", $input);
            $username = $emailParts[0];
            $hiddenUsername = substr($username, 0, 2) . str_repeat("*", strlen($username) - 2);
            $hiddenEmailDomain = substr($emailParts[1], 0, 2) . str_repeat("*", strlen($emailParts[1]) - 3) . $emailParts[1][strlen($emailParts[1])-1];
            return $hiddenUsername . "@" . $hiddenEmailDomain;

        } else {
            return preg_replace('/(\d{3})\d{3}(\d{3})/', '$1****$2', $input);;
        }

    }
}
