<?php

namespace Stilmark\Parse;

use FastRoute;

// https://github.com/tochix/shapes-api/blob/master/src/Service/Http/Router.php

class Route
{

    const HANDLER_DELIMITER = '@';

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
		        $routes->addRoute($request, $pattern, $classMethod);
		    }
		});

		$Request = Request::new();

		$routeInfo = $dispatcher->dispatch(Request::method(), Request::path());

		switch ($routeInfo[0]) {

			case FastRoute\Dispatcher::NOT_FOUND:
                header('HTTP/1.0 404 Not Found');
                return ['error' => '404 Not Found'];

			case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                header('HTTP/1.0 405 Method Not Allowed');
                return ['error' => '405 Method Not Allowed'];

			case FastRoute\Dispatcher::FOUND:
                list($state, $handler, $vars) = $routeInfo;
                list($class, $method) = explode(self::HANDLER_DELIMITER, $handler, 2);

                $container = (isset($_ENV['NAMESPACE']) ? $_ENV['NAMESPACE'].'\\':'').$class;
                $data = (new $container())->$method(...array_values($vars));

			    return ['data' => $data, 'route' => $routeInfo];

            default:
                header('HTTP/1.0 410 Gone');
                return ['error' => '410 Gone'];

		}
	}

	public static function getRouteMap()
	{
	    if (@($routes = include_once 'routes.php')) {
	        return $routes;
        } else {
	        die('Missing routes.php');
        }
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