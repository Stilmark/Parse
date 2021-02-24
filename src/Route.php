<?php

namespace Stilmark\Parse;

use FastRoute\RouteCollector;
use FastRoute\simpleDispatcher;
use FastRoute\cachedDispatcher;
use FastRoute\Dispatcher;

class Route
{

	public static function redirect($url) {
	    header('Location: '.filter_var($url, FILTER_SANITIZE_URL));
	    exit;
	}

}