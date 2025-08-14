<?php

namespace App\Routes;

use \AllowDynamicProperties;

#[AllowDynamicProperties]
class Router extends \stdClass {
    protected array $routes;
    protected string $routesFilePath;

    public function __construct() {
        $this->routes = $this->generateRoutes();
        $this->routesFilePath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR
            . "routes" . DIRECTORY_SEPARATOR . "web.json";
    }

    /** Checks the Router's "bag" of routes for a provided route name / path.
    *
    * @param string $search         The name / path of the route you are
    *                               looking for.
    *
    * @return bool
    */
    public function hasRoute(string $search) : bool {
        if ($this->hasRouteName($search) || $this->hasRoutePath($search)) {
            return true;
        }

        return false;
    }

    private function hasRouteName(string $routeName) {
        foreach ($this->routes as $route) {
            if ($route->getName() === $routeName) {
                return true;
            }
        }

        return false;
    }

    private function hasRoutePath(string $routePath) {
        return $this->getRouteByPath($routePath) !== null;
    }

    public function getRoutes() : array {
        return $this->routes;
    }


    /** Get a specified route from the Route Bag by its name. Returns null if a
    * matching route cannot be located.
    *
    * @param string $routeName      The name of the route you are searching for.
    * @return null|Route
    */
    public function getRouteByName(string $search) : ?Route {
        foreach ($this->routes as $route) {
            if ($route->getName() === $search) {
                return $route;
            }
        }

        return null;
    }

    /** Get a specified route from the Rotue Bag by its path. Returns null if 
    * a matching route cannot be located.
    * 
    * @param string $searchPath
    * @return null|Route
    */
    public function getRouteByPath(string $searchPath) : ?Route {
        foreach ($this->routes as $route) {
            $routes_match = true;

            // split the two paths for token comparison
            $route_parts = explode("/", $route->getPath());
            $search_parts = explode("/", $searchPath);

            // paths must be the same length
            if (count($route_parts) === count($search_parts)) {
                // loop through the tokens
                for ($i = 0; $i < count($route_parts); $i++) {
                    // compare the tokens
                    $r_token = $route_parts[$i];
                    $s_token = $search_parts[$i];

                    // Only only match the tokens if the route token is not a 
                    // palceholder. In case of placeholders, we only care that
                    // it exists.
                    if (!str_starts_with($r_token, "{")) {
                        // Indicate that the tokens dont match
                        if ($r_token !== $s_token) {
                            $routes_match = false;
                        }
                    }
                }
            } else {
                $routes_match = false;
            }

            // Return the route if it ended up matching.
            if ($routes_match) {
                return $route;
            }
        }

        // Return null if there were not routes found by the provided path.
        return null;
    }

    /** Generate the router's route "bag". Routes can be configured in the 
    * /routes/web.json file. The bag consists of an associative array where 
    * each key is the name of a route, and information about the route and its
    * requirements is the value.
    *
    * @return array
    *
    *   Route Example
    *
    *   {
    *       "home" : {
    *           "name" : "home",
    *           "path" : "/",
    *           "controller" : "PageController",
    *           "function" : "index"
    *      }...
    *   }
    * 
    * Variable route parameters can be set by including the path element in 
    * curly brackets. The value placed within the brackets becomes an 
    * accessible within the routes controller method.
    *
    * Example: "path" : "/path/{optional}/rest-of-path..."
    */
    private function generateRoutes() : array {
        $routes = [];

        if (file_exists("../routes/web.json")) {
            $contents = file_get_contents("../routes/web.json");
            $routes_info = json_decode($contents, true);

            // turn the route info into Route objects
            foreach ($routes_info as $r) {
                $routes[] = new Route($r);
            }
        }

        return $routes;
    }
}
