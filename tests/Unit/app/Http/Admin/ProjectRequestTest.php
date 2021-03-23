<?php

namespace Tests\Unit\app\Http\Admin;

use PHPUnit\Framework\TestCase;

class ProjectRequestTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @param array
     * @dataProvider projectRequestProvider
     */
    public function testProjectRequest()
    {
        $this->assertTrue(true);
    }

    public function projectRequestProvider()
    {
        return [
            '新規 失敗' => [
                'name' => null,
                'code' => null,
            ],

        ];
    }
}
