<?php

namespace App\Utils;

use Carbon\Carbon;

class StrtotimeConverter
{
    private const HOURMINUTESPATTERN = '/^[0-9]{2}:[0-5][0-9]/';
    private const COLONDLIMITER = '/:/';
    private const COLON = ':';

    private const TIME_FORMAT_HI = 'H:i';
    private const TIME_FORMAT_HIS = 'H:i:s';

    public static function strHourToIntMinute(String $hour)
    {
        if (!preg_match(self::HOURMINUTESPATTERN, $hour)) return false;

        $data = preg_split(self::COLONDLIMITER, $hour);

        return intval($data[0]) * Carbon::MINUTES_PER_HOUR + intval($data[1]);
    }

    public static function intMinuteToStrHour(int $minutes)
    {
        $intHour = intdiv($minutes, Carbon::MINUTES_PER_HOUR);
        $modMinutes = $minutes % Carbon::MINUTES_PER_HOUR;

        return str_pad($intHour, 2, '0', STR_PAD_LEFT) . self::COLON . str_pad($modMinutes, 2, '0', STR_PAD_LEFT);
    }

    public static function convertTimeFormat(String $time)
    {
        if (preg_match('/^([0-1][0-9]|[2][0-4]):[0-5][0-9]:[0-9]{2,3}$/', $time)) {
            return preg_replace('/:[0-9]{2}$/', '', $time);
        } else if (preg_match('/^([0-1][0-9]|[2][0-4]):[0-5][0-9]/', $time)) {
            return $time . ':00';
        }

        return false;
    }
}
