<?php

namespace Stilmark\Parse;

use FastRoute;

// https://github.com/tochix/shapes-api/blob/master/src/Service/Http/Router.php

class Route
{

    const HANDLER_DELIMITER = '@';
    public static $reponseCodes = [
        200 => 'Ok',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        410 => 'Gone',
        500 => 'Internal Server Error'
    ];

	public static function redirect($url)
	{
	    header('Location: '.filter_var($url, FILTER_SANITIZE_URL));
	    exit;
	}

	public static function header($responseCode)
    {
        if (isset(self::$reponseCodes[$responseCode])) {
            header('HTTP/1.0 '.$responseCode.' '.self::$reponseCodes[$responseCode]);
        }
    }

    public static function errorResponse($responseCode, $message = '') {
        self::header($responseCode);
	    $message = (empty($message) && isset(self::$reponseCodes[$responseCode])) ? self::$reponseCodes[$responseCode]:$message;
        return ['error' => $responseCode, 'message' => $message];
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

		$routeInfo = $dispatcher->dispatch(Request::method(), Request::path());
		
        switch ($routeInfo[0]) {

			case FastRoute\Dispatcher::NOT_FOUND:
			    return self::errorResponse(404);

			case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return self::errorResponse(405);

			case FastRoute\Dispatcher::FOUND:
                list($state, $handler, $vars) = $routeInfo;
                list($class, $method) = explode(self::HANDLER_DELIMITER, $handler, 2);

                $GLOBALS['URI_ARGUMENTS'] = $vars;
                $container = ($_ENV['NAMESPACE'] ?? '').($_ENV['CONTROLLER'] ?? '').'\\'.$class;
                $response = (new $container())->$method();

                if (isset($response['error'])) {
                    if (!isset($response['message'])) {
                        $response['message'] = '';
                    }
                    return self::errorResponse($response['error'], $response['message']);
                } else {
                    self::header(200);
                    return $response;
                }

            default:
                return self::errorResponse(410);
		}
	}

	public static function getRouteMap()
	{
	    if (@($routes = include_once $_ENV['APPROOT'].'/routes.php')) {
	        return $routes;
        } else {
	        die('Missing: '.$_ENV['APPROOT'].'/routes.php');
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