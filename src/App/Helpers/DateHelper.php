<?php

namespace Raftx24\Helper\App\Helpers;

class DateHelper
{
    public static function toJdate($year, $month, $day)
    {
        $dates = [];
        $dates['year'] = $year;
        $dates['month'] = $month;
        $dates['day'] = $day;
        if ($year > 1800) {
            $date = strtotime($year . '/' . $month . '/' . $day);
            $dates['year'] = (new Jdf())->jdate('Y', $date);
            $dates['month'] = (new Jdf())->jdate('m', $date);
            $dates['day'] = (new Jdf())->jdate('d', $date);
        }

        return $dates;
    }

    public static function toGeo($year, $month, $day)
    {
        $dates = [
            'year' => $year,
            'month' => $month,
            'day' => $day,
        ];

        if ($year < 1800) {
            $date = (new Jdf())->jmktime(0, 0, 0, $month, $day, $year);
            $dates = [
                'year' => date('Y', $date),
                'month' => date('m', $date),
                'day' => date('d', $date),
            ];
        }

        return $dates;
    }
}
