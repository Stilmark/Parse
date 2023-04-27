<?php

namespace Stilmark\Parse;

use Stilmark\Parse\Dump;

class Vardump
{
	public static function json(
		$array = []
	){
		echo Dump::json($array, false);
		exit;
	}

	public static function csv(
		$array = []
	){
		echo Dump::csv($array);
		exit;
	}

}