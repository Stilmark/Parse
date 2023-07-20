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
		$array = []
	){
		echo Dump::csv($array);
		exit;
	}

}