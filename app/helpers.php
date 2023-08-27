<?php

use Illuminate\Support\Facades\Http;

function str_extract($str, $pattern, $get = null, $default = null)
{
    $result = [];
    preg_match($pattern, $str, $matches);
    preg_match_all('/(\(\?P\<(?P<name>.+)\>\.\+\)+)/U', $pattern, $captures);
    $names = $captures['name'] ?? [];
    foreach ($names as $name) {
        $result[$name] = $matches[$name] ?? null;
    }
    return $get ? $result[$get] ?? $default : $result;
}

function wrap_str($str = '', $first_delimiter = "'", $last_delimiter = null)
{
    if (!$last_delimiter) {
        return $first_delimiter . $str . $first_delimiter;
    }

    return $first_delimiter . $str . $last_delimiter;
}

function getExtensionImageFromUrl($url)
{
    $url = explode('.', $url);
    $extension = end($url);
    return $extension;
}

function clearCacheNode()
{
    try {
        Http::withOptions(['verify' => false])
            ->asForm()
            ->post(env('WA_URL_SERVER') . '/backend-clearCache');
        return true;
    } catch (\Throwable $th) {
        return false;
    }
}

function setEnv(string $key, string $value)
{
    $env = array_reduce(
        file(base_path('.env'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES),
        function ($carry, $item) {
            list($key, $val) = explode('=', $item, 2);

            $carry[$key] = $val;

            return $carry;
        },
        []
    );
    $env[$key] = $value;
    foreach ($env as $k => &$v) {
        $v = "{$k}={$v}";
    }

    file_put_contents(base_path('.env'), implode("\r\n", $env));
}

?>
