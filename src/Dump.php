<?php

namespace Stilmark\Parse;

use Stilmark\Parse\Str;

class Dump
{
	public $dump, $mimetype, $filename;

	public static function json($array = [])
	{
		$obj = new Dump;
		$obj->mimetype = 'application/json';
		$obj->dump = json_encode($array);
		return $obj;
	}

	public static function csv($array = [])
	{
		$obj = new Dump;
		$obj->mimetype = 'text/csv';
		$obj->dump = implode(';', array_keys(current($array))).PHP_EOL;
		foreach($array AS $row) {
			$obj->dump .= implode(';', array_values($row)).PHP_EOL;
		}
		return $obj;
	}

	public static function php($array = [])
	{
		$obj = new Dump;
		$obj->mimetype = 'application/x-httpd-php';
		$obj->dump = var_export($array, true);
		return $obj;
	}

	public static function table($array = [], $attr = [])
	{
		$obj = new Dump;
		$obj->mimetype = 'text/html';
		$table = Str::make();

		// Header
		foreach(array_keys(current($array)) AS $column) {
			$table->append( Str::make($column)->wrapTag('td') );
		}
		$table->wrapTag('tr')->wrapTag('thead');

		// Body
		$tbody = Str::make();
		foreach($array AS $row) {
			$tr = Str::make();
			foreach($row AS $value) {
				$tr->append( Str::make($value)->wrapTag('td') );
			}
			$tr->wrapTag('tr');
			$tbody->append($tr);
		}

		$table->append($tbody->wrapTag('tbody'));
		$obj->dump = (String) $table->wrapTag('table', $attr);

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
			header('Content-length: '.mb_strlen($this->dump));
		}
	}

	function getFilename() {
		if (!isset($this->filename) || empty($this->filename)) {
			$this->filename = 'unknown';
		}
		return $this->filename;
	}

	function file($filename)
	{
		$this->filename = $filename;
		return $this;
	}

	function dump($filename = '')
	{
		if (!empty($filename)) {
			$this->filename = $filename;
		}
		file_put_contents($this->getFilename(), $this->dump);
	}

	function append($filename = '')
	{
		if (!empty($filename)) {
			$this->filename = $filename;
		}
		file_put_contents($this->getFilename(), $this->dump, FILE_APPEND);
	}

	function log($filename = '')
	{
		if (!empty($filename)) {
			$this->filename = Str::make($filename);
			if (isset($_ENV['LOGS'])) {
				$this->filename->prepend($_SERVER['DOCUMENT_ROOT'].$_ENV['LOGS']);
			}
		}
		file_put_contents($this->getFilename(), $this->dump, FILE_APPEND);
	}

	function download()
	{
		$this->filename = $this->getFilename();
		$this->headers();
		echo $this->dump;
		exit;
	}

    public function __toString()
    {
    	$this->headers();
        return trim($this->dump).PHP_EOL;
    }

}