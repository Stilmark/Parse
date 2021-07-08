<?php

namespace Tests;

use Stilmark\Parse\Str;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{

    public function test_make_string()
    {
        $set = ['abc def', 100, '22', '%%&', 'æøå'];

        foreach($set AS $str) {
            $result = Str::make($str);

            $this->assertIsString($result->str);

            $this->assertEquals(
                mb_detect_encoding($result->str, 'UTF-8'),
                'UTF-8'
            );
        }
    }

    public function test_string_to_upper()
    {
        $set = [
            'abc' => 'ABC',
            'Abc' => 'ABC',
            '1bc' => '1BC',
            'æøå' => 'ÆØÅ',
            'é%# ' => 'É%# ',
        ];

        foreach($set AS $str => $expected) {
            $result = Str::make($str)->upper();
            $this->assertSame($expected, $result->str);
        }

    }

}