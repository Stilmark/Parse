<?php

namespace Stilmark\Parse;

use Stilmark\Parse\Str;

class Dump
{
	public $data, $mimetype, $filename;

	public static function json(
		$array = [], 
		$flag = JSON_PRETTY_PRINT
	){
		$obj = new Dump;
		$obj->mimetype = 'application/json';

		$obj->data = json_encode($array, $flag);
		return $obj;
	}

	public static function csv(
		$array = []
	){
		$obj = new Dump;
		$obj->mimetype = 'text/csv';
		$obj->data = implode(';', array_keys(current($array))).PHP_EOL;
		foreach($array AS $row) {
			$obj->data .= implode(';', array_values($row)).PHP_EOL;
		}
		return $obj;
	}

	public static function php(
		$array = []
	){
		$obj = new Dump;
		$obj->mimetype = 'application/x-httpd-php';
		$obj->data = var_export($array, true);
		return $obj;
	}

	public static function table(
		$array = [],
		$attr = []
	){
		$obj = new Dump;
		$obj->mimetype = 'text/html';
		$table = Str::set();

		// Header
		foreach(array_keys(current($array)) AS $column) {
			$table->append( Str::set($column)->wrapTag('td') );
		}
		$table->wrapTag('tr')->wrapTag('thead');

		// Body
		$tbody = Str::set();
		foreach($array AS $row) {
			$tr = Str::set();
			foreach($row AS $value) {
				$tr->append( Str::set($value)->wrapTag('td') );
			}
			$tr->wrapTag('tr');
			$tbody->append($tr);
		}

		$table->append($tbody->wrapTag('tbody'));
		$obj->data = (String) $table->wrapTag('table', $attr);

		return $obj;
	}

	function headers()
	{
        if (headers_sent()) {
            return;
        }

		if (!empty($this->mimetype)) {
			header('Content-Type: '.$this->mimetype.'; charset=UTF-8');
		}

		if (!empty($this->filename)) {
			header('Content-Disposition: attachment; filename='.$this->filename);
			header('Content-length: '.mb_strlen($this->data));
		}
	}

	function getFilename() {
		if (!isset($this->filename) || empty($this->filename)) {
			$this->filename = 'unknown';
		}
		return $this->filename;
	}

	function file(
		$filename
	){
		$this->filename = $filename;
		return $this;
	}

	function write(
		$filename = ''
	){
		if (!empty($filename)) {
			$this->filename = $filename;
		}
		file_put_contents($this->getFilename(), $this->data);
	}

	function append(
		$filename = ''
	){
		if (!empty($filename)) {
			$this->filename = $filename;
		}
		file_put_contents($this->getFilename(), $this->data, FILE_APPEND);
	}

	function log(
		$filename = ''
	){
		if (!empty($filename)) {
			$this->filename = Str::set($filename);
			if (isset($_ENV['LOGS'])) {
				$this->filename->prepend($_SERVER['DOCUMENT_ROOT'].$_ENV['LOGS']);
			}
		}
		file_put_contents($this->getFilename(), $this->data, FILE_APPEND);
	}

	function download()
	{
		$this->filename = $this->getFilename();
		$this->headers();
		echo $this->data;
		exit;
	}

    public function __toString()
    {
    	$this->headers();
        return trim($this->data).PHP_EOL;
    }

}