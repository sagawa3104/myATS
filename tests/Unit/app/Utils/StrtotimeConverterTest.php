<?php

namespace Tests\Unit\app\Utils;

use App\Utils\StrtotimeConverter;
use PHPUnit\Framework\TestCase;

class StrtotimeConverterTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testStrHourToIntMinute()
    {
        $hour = "08:00";
        $this->assertEquals(480, StrtotimeConverter::strHourToIntMinute($hour));

        $notHour = "a";
        $this->assertFalse(StrtotimeConverter::strHourToIntMinute($notHour));

        $notHour = "00:99";
        $this->assertFalse(StrtotimeConverter::strHourToIntMinute($notHour));

        $notHour = "100:100";
        $this->assertFalse(StrtotimeConverter::strHourToIntMinute($notHour));
    }

    public function testIntMinuteToStrHour()
    {
        $minute = 120;
        $this->assertIsString(StrtotimeConverter::intMinuteToStrHour($minute));
        $this->assertEquals("02:00", StrtotimeConverter::intMinuteToStrHour($minute));

        $minusValue = 601;
        $this->assertEquals("10:01", StrtotimeConverter::intMinuteToStrHour($minusValue));

        $minusValue = -1200;
        $this->assertEquals("-20:00", StrtotimeConverter::intMinuteToStrHour($minusValue));
    }
}
