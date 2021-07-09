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
            $result = Str::make($str)->str;

            $this->assertIsString($result);

            $this->assertEquals(
                mb_detect_encoding($result, 'UTF-8'),
                'UTF-8'
            );
        }
    }

    public function test_string_to_upper_case()
    {
        $set = [
            'abc' => 'ABC',
            'Abc' => 'ABC',
            '1bc' => '1BC',
            'æøå' => 'ÆØÅ',
            'é%# ' => 'É%# ',
        ];

        foreach($set AS $str => $expected) {
            $result = Str::make($str)->upper()->str;
            $this->assertSame($expected, $result);
        }
    }

    public function test_string_to_lower_case()
    {
        $set = [
            'ABC' => 'abc',
            '1BC' => '1bc',
            'ÆØÅ' => 'æøå',
            'É%# ' => 'é%# '
        ];

        foreach($set AS $str => $expected) {
            $result = Str::make($str)->lower()->str;
            $this->assertSame($expected, $result);
        }
    }

    public function test_string_capitalize()
    {
        $set = [
            'abc' => 'Abc',
            'Abc' => 'Abc',
            '1bc' => '1bc',
            'æøå' => 'Æøå',
            'é%# ' => 'É%# ',
        ];

        foreach($set AS $str => $expected) {
            $result = Str::make($str)->capitalize()->str;
            $this->assertSame($expected, $result);
        }
    }

    public function test_string_to_title_case()
    {
        $set = [
            'mary had a Little lamb and she loved it so' => 'Mary Had A Little Lamb And She Loved It So',
            'østers & hatte a/s' => 'Østers & Hatte A/S',
        ];

        foreach($set AS $str => $expected) {
            $result = Str::make($str)->title()->str;
            $this->assertSame($expected, $result);
        }
    }

    public function test_string_to_camel_case()
    {
        $set = [
            'mary had a Little lamb' => 'maryHadALittleLamb',
            'Larry & david' => 'larryDavid',
            'big-bird' => 'bigBird',
            '122 applé' => '122Appl',
        ];

        foreach($set AS $str => $expected) {
            $result = Str::make($str)->camel()->str;
            $this->assertSame($expected, $result);
        }
    }

    public function test_string_trim()
    {
        $set = [
            'mary had a Little lamb' => 'maryHadALittleLamb',
            'Larry & david' => 'larryDavid',
            'big-bird' => 'bigBird',
            '122 applé' => '122Appl',
        ];

        foreach($set AS $str => $expected) {
            $result = Str::make($str)->trim()->str;
            $this->assertSame($expected, $result);
        }
    }

}