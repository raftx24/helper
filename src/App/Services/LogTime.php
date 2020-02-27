<?php

namespace Raftx24\Helper\App\Services;

use Illuminate\Support\Facades\Log as LaravelLog;

class LogTime
{
    public static $enable = false;
    private static $time;

    public static function time($msg)
    {
        self::$time = self::$time ?? microtime(true);
        $result = number_format(round((microtime(true) - self::$time)* 1000));
        self::resetTime();

        if (self::$enable) {
            self::echo($msg." at ({$result} ms)");
        }

        return $result;
    }

    public static function resetTime()
    {
        self::$time = microtime(true);
    }

    private static function echo($msg)
    {
        LaravelLog::debug(rtrim($msg, PHP_EOL).PHP_EOL);
    }
}
