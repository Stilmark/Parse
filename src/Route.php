<?php

namespace Stilmark\Parse;

use FastRoute;

// https://github.com/tochix/shapes-api/blob/master/src/Service/Http/Router.php

class Route
{

	public static function redirect($url)
	{
	    header('Location: '.filter_var($url, FILTER_SANITIZE_URL));
	    exit;
	}

	public static function dispatch()
	{
		$route_map = self::getRouteMap();
		$route_patterns = self::compileRoutes($route_map);

		$dispatcher = \FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $routes) use ($route_patterns) {
		    foreach($route_patterns AS $pattern => $args) {
		        list($request, $classMethod) = $args;
		        list($className, $method) = explode('@', $classMethod);
		        $routes->addRoute($request, $pattern, [$className, $method]);
		    }
		});

		$routeInfo = $dispatcher->dispatch(Request::method(), Request::path());

		return $routeInfo;
	}

	public static function getRouteMap()
	{
		return [
		    '/' => 	['GET', 'SiteController@home'],
		    '/api' => [
		        '/users' =>         ['GET', 'UserController@index'],
		        '/user' => [
		            '/{id:\d+}' =>  ['GET', 'UserController@show'],
		        ]
		    ]
		];
	}

	public static function compileRoutes($arr, $path = '')
	{
	    global $route_list;
	    foreach ($arr as $key => $value) {
	        if (is_array($value)) {
	            self::compileRoutes($value, $path.$key);
	        } else {
	            $route_list[$path][] = $value;
	        }
	    }
	    return $route_list;
	}

}