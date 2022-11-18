<?php

require('../vendor/autoload.php');

use Stilmark\Parse\Dump;
use Stilmark\Parse\iCal;

$response = iCal::process('https://calendar.google.com/calendar/ical/c_4380ba8f58c9905e989ff18e44927cccdc24469200c6ad0de0eb28226986a967%40group.calendar.google.com/public/basic.ics');

echo Dump::json($response, JSON_PRETTY_PRINT);

/*

DROP TABLE IF EXISTS `calendars`;
CREATE TABLE `calendars` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` tinytext,
  `url` tinytext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

- calendars
	- id
	- user_id
	- name
	- description
	- timezone
	- url
	- created_at
	- updated_at
	- processed_at
- timesheets
	- calendar_id
	- start

*/