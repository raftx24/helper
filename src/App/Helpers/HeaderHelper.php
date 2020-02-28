<?php

namespace Raftx24\Helper\App\Helpers;

class HeaderHelper
{
    public static function getClientIPAddress()
    {
        return $_SERVER["REMOTE_ADDR"] ?? '';
    }

    public static function getCurrentUrl()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function getCurrentHost()
    {
        return $_SERVER['SERVER_NAME'];
    }


}
