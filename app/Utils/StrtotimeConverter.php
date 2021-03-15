<?php

namespace App\Utils\Consts;

class StrtotimeConverter
{
    //1分は60秒
    private const SECONDSFORMINUTE = 60;
    //1時間は3600秒
    private const SECONDSFORHOUR = 3600;
    //1時間は60分
    private const MINUTESFORHOUR = 60;

    private const HOURMINUTESPATTERN = '/^[0-9]{2}:[0-5][0-9]/';
    private const COLON = ':';

    public static function strHourToIntMinute(String $hour){
        if(!preg_match(self::HOURMINUTESPATTERN, $hour)) return false;

        $data = preg_split(self::COLON, $hour);

        return intval($data[0]) * self::MINUTESFORHOUR + intval($data[1]);
    }

    public static function intMinuteToStrHour(int $minutes ){
        $intHour = intdiv($minutes, self::MINUTESFORHOUR);
        $modMinutes = $minutes % self::MINUTESFORHOUR;

        return (String)$intHour . self::COLON. (String)$modMinutes;
    }

}

