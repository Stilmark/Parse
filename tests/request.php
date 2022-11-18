<?php

require('../vendor/autoload.php');

use Stilmark\Parse\Dump;
use Stilmark\Parse\Request;

$_SERVER['REMOTE_ADDR'] = '1.1.1.1';
$_SERVER['SERVER_NAME'] = 'www.php.net';
$_SERVER['REQUEST_URI'] = '/api/user/66/?large=smile';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['QUERY_STRING'] = 'foo=bar';
$GLOBALS['URI_ARGUMENTS'] = ['id' => 66, 'norm' => 'out', 'person' => 'taken'];

echo Dump::json( Request::args(['id', 'person']));