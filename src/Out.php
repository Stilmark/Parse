<?php

namespace Stilmark\Parse;

class Out
{
	public static function json($array, $filename = null) {
		if (!is_array($array)) {
			$array = [$array];
		}
		$var = json_encode($array).PHP_EOL;
		$headers = ['type' => 'application/json'];
		if (!is_null($filename)) {
			$headers['filename'] = $filename;
		}
		self::headers($headers);
		echo $var;
	}

	public static function csv($array, $filename = null) {

		if (!is_array($array)) {
			$array = [$array];
		}
		$var = implode(';', array_keys(current($array))).PHP_EOL;
		foreach($array AS $row) {
			$var .= implode(';', array_values($row)).PHP_EOL;
		}
		self::headers(['type' => 'text/csv']);
		echo $var;
	}

	public static function headers($args) {

		if (isset($args['type'])) {
			header('Content-Type: '.$args['type']);
		}

		if (isset($args['filename'])) {
			header('Content-Disposition: attachment; filename='.$args['filename']);
		}

		// header("Pragma: no-cache");
		// header("Expires: 0");

	}

}