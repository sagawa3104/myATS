<?php

namespace Tests\Unit\app\Utils;

use App\Utils\StrtotimeConverter;
use Exception;
use PHPUnit\Framework\TestCase;

class StrtotimeConverterTest extends TestCase
{
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

    /**
     * @test
     * @dataProvider convertTimeFormatDataProvider
     */
    public function testConvertTimeFormat($param, $expected)
    {
        $this->assertEquals($expected, StrtotimeConverter::convertTimeFormat($param));
    }

    public function convertTimeFormatDataProvider()
    {
        return [
            'NOT STRING' => [
                1000,
                false,
            ],
            'INVALID TIME FORMAT STRING JUST STRING' => [
                'hoge',
                false,
            ],
            'INVALID TIME FORMAT STRING OVER 24 HOUR A' => [
                '25:00:00',
                false,
            ],
            'INVALID TIME FORMAT STRING OVER 24 HOUR B' => [
                '30:00:00',
                false,
            ],
            'CONVERT LONG TO SHORT' => [
                '10:00:00',
                '10:00',
            ],
            'CONVERT SHORT TO LONG' => [
                '10:00',
                '10:00:00',
            ],
        ];
    }
}
