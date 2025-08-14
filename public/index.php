<?php

error_reporting(E_ERROR | E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once "../vendor/autoload.php";

use Dotenv\Dotenv;
use App\Request;
use App\Routes\Router;

$dotenv = Dotenv::createImmutable(dirname(__DIR__)); // project root
$dotenv->load();

$request = new Request();
$router = new Router();

// Try to call the requested URI
if ($router->hasRoute($request->getRequestURI())) {
    try {
        $route = $router->getRouteByPath($request->getRequestURI());

        // Get the controller and method from the found route
        $route_controller_name = '\\App\\Controllers\\' . $route->getControllerName();
        $controller = new ReflectionClass($route_controller_name);

        // Check that the route supports the request method
        if (!in_array($request->getRequestMethod(), $route->getMethods())) {
            echo "405 Method Not Allowed";  // todo: Create the 405 page.
        }

        // Try to call the method
        if (!$controller->hasMethod($route->getHandler())) {
            throw new \Exception("Method {$route->getHandler()} not found on "
               ."class {$controller->getName()}", 500); 
        };

        // the return from the controller should be a string of some type.
        echo $controller->getMethod($route->getHandler())
            ->invoke($controller->newInstance($request, $route));

    } catch (\Exception $e) {
        // todo: Create the 500 page. 
        dd("ERROR: " . $e->getCode(), $e->getMessage(), $e->getTrace());
    }

} else {
    // Error out if the route cannot be found.
    echo "404 Page Not Found";  // todo: Create the 404 page.
}
