<?php

namespace Stilmark\Parse;

use Stilmark\Parse\Dump;

class Vardump
{
	public static function json(
		$array = [],
		$flag = false
	){
		echo Dump::json($array, $flag);
		exit;
	}

	public static function csv(
		Array $array = [],
		String $filename = null
	){
		echo Dump::csv($array, $filename);
		exit;
	}

}