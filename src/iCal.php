<?php

namespace Stilmark\Parse;

use Stilmark\Parse\Str;

class iCal {

	private $url, $data;

	function __construct()
	{
		$this->meta = [
			'generator' => null,
			'version' => null,
			'timezone' => null,
			'name' => null,
			'description' => null,
			'events' => 0
		];
		$this->events = [];
	}

	static function process(String $url) {
		return (new iCal())->loadFile($url)->parseFile();
	}

	function loadFile(String $url)
	{
		$this->data = file($url);
		return $this;
	}

	function limit(Int $limit)
	{
		$this->limit = $limit;
		return $this;
	}

	function parseFile()
	{
		if (is_array($this->data)) {

			foreach($this->data AS $n => $line) {
				$row = Str::set($line)->trim();

				// Parse meta data
				if (!isset($metadata)) {
					if ($n == 0 && $row->str != 'BEGIN:VCALENDAR') {
						return ['error' => 'Invalid iCal format'];
					}
					if ($row->beginsWith('PRODID:') && preg_match('/^PRODID:(.+)/', $row->str, $match)) {
						$this->meta['generator'] = $match[1];
					} elseif ($row->beginsWith('X-WR-CALNAME:') && preg_match('/^X-WR-CALNAME:(.+)/', $row->str, $match)) {
						$this->meta['name'] = $match[1];
					} elseif ($row->beginsWith('X-WR-CALDESC:') && preg_match('/^X-WR-CALDESC:(.+)/', $row->str, $match)) {
						$this->meta['description'] = $match[1];
					} elseif ($row->beginsWith('X-WR-TIMEZONE:') && preg_match('/^X-WR-TIMEZONE:(.+)/', $row->str, $match)) {
						date_default_timezone_set($match[1]);
						$this->meta['timezone'] = $match[1];
					}
				}

				if ($row->equals('BEGIN:VEVENT')) {
					$metadata = true;
					$event = [
						'uid' => null,
						'title' => null,
						'start' => null,
						'end' => null,
						'hours' => null,
						'created' => null,
						'updated' => null
					];
				} elseif ($row->equals('END:VEVENT')) {
					if (isset($event['hours'])) {
						$this->events[] = $event;
						$this->meta['events']++;
					}

					if (isset($this->limit) && $this->limit == $this->meta['events']) {
						return $this;
					}
				}

				if ($row->beginsWith('DTSTART:') && preg_match('/^DTSTART:(.+)/', $row->str, $match)) {
					$event['start'] = date('Y-m-d H:i:s', strtotime($match[1]));
				} elseif ($row->beginsWith('DTEND:') && preg_match('/^DTEND:(.+)/', $row->str, $match)) {
					$event['end'] = date('Y-m-d H:i:s', strtotime($match[1]));
					$event['hours'] = (strtotime($event['end'])-strtotime($event['start'])) / 3600;
				} elseif ($row->beginsWith('SUMMARY:') && preg_match('/^SUMMARY:(.+)/', $row->str, $match)) {
					$event['title'] = $match[1];
				} elseif ($row->beginsWith('CREATED:') && preg_match('/^CREATED:(.+)/', $row->str, $match)) {
					$event['created'] = date('Y-m-d H:i:s', strtotime($match[1]));
				} elseif ($row->beginsWith('LAST-MODIFIED:') && preg_match('/^LAST-MODIFIED:(.+)/', $row->str, $match)) {
					$event['updated'] = date('Y-m-d H:i:s', strtotime($match[1]));
				} elseif ($row->beginsWith('UID:') && preg_match('/^UID:(.+)/', $row->str, $match)) {
					$event['uid'] = $match[1];
				}
			}

			return $this;
		}
	}
}