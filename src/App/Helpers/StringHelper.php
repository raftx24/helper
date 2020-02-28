<?php

namespace Raftx24\Helper\App\Helpers;

class StringHelper
{
    private static $persianNumbers = [
        0 => '۰',
        1 => '١',
        2 => '۲',
        3 => '۳',
        4 => '۴',
        5 => '۵',
        6 => '۶',
        7 => '۷',
        8 => '۸',
        9 => '۹',
    ];

    private static $arabicNumbers = [
        0 => '٠',
        1 => '۱',
        2 => '٢',
        3 => '٣',
        4 => '٤',
        5 => '٥',
        6 => '٦',
        7 => '٧',
        8 => '٨',
        9 => '٩',
    ];

    private static $arabicCharacters = [
        'ك' => 'ک',
        'دِ' => 'د',
        'بِ' => 'ب',
        'زِ' => 'ز',
        'ذِ' => 'ذ',
        'شِ' => 'ش',
        'سِ' => 'س',
        'ى' => 'ی',
        'ي' => 'ی',
        '١' => '۱',
        '٢' => '۲',
        '٣' => '۳',
        '٤' => '۴',
        '٥' => '۵',
        '٦' => '۶',
        '٧' => '۷',
        '٨' => '۸',
        '٩' => '۹',
        '٠' => '۰',
    ];

    private static $spaces = '\x{0020}\x{2000}-\x{200F}\x{2028}-\x{202F}';
    private static $punctuation = '\x{060C}\x{061B}\x{061F}\x{0640}\x{066A}\x{066B}\x{066C}';


    public static function sanitize($input)
    {
        $input = preg_replace('/^['.static::$spaces.']*$/u', '', $input);
        $input = preg_replace('/^['.static::$punctuation.']*$/u', '', $input);
        $input = preg_replace('/^['.static::$punctuation.']*$/u', '', $input);
        $input = str_replace(array_values(static::$arabicNumbers), array_keys(static::$arabicNumbers), $input);
        $input = str_replace(array_values(static::$persianNumbers), array_keys(static::$persianNumbers), $input);

        return str_replace(array_keys(static::$arabicCharacters), array_values(static::$arabicCharacters), $input);
    }

    public static function toFarsiNumber($str)
    {
        return str_replace(
            array_keys(static::$persianNumbers),
            array_values(static::$persianNumbers),
            $str
        );
    }
}
