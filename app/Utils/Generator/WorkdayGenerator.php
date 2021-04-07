<?php

namespace App\Utils\Generator;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class WorkdayGenerator
{

    public static function periodPerDay($start, $end, $onlyWeekdays = true)
    {
        $period = CarbonPeriod::since($start)->days()->until($end);

        $days = collect();
        foreach ($period as $day) {
            if ($onlyWeekdays) {
                if ($day->isWeekday()) {
                    $days->push($day->format('Y-m-d'));
                }
            } else {
                $days->push($day->format('Y-m-d'));
            }
        }

        return $days;
    }
}
