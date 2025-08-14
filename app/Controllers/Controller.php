<?php

namespace App\Controllers;

use App\Request;
use App\Routes\Route;

abstract class Controller {
    protected array $path_vars;
    protected \Twig\Loader\FilesystemLoader $loader;
    protected \Twig\Environment $twig;

    public function __construct(Request $request, Route $route)
    {
        // idk what to put here yet
        $this->path_vars = $this->parsePathVariables(
            $request->getRequestURI(), 
            $route->getPath()
        );

        $this->loader = new \Twig\Loader\FilesystemLoader("../templates/");
        $this->twig = new \Twig\Environment($this->loader);
    }

    public function getPathVariables() : array {
        return $this->path_vars;
    }

    private function parsePathVariables(
        string $requestURI, 
        string $routePath
    ) : array {
        $return_arr = [];
        $request_parts = explode('/', $requestURI);
        $route_parts = explode('/', $routePath);

        for ($i = 0; $i < count($route_parts); $i++) {
            if (str_starts_with($route_parts[$i], "{")) {
                $value = $request_parts[$i];
                $key = str_replace(['{', '}'], '', $route_parts[$i]);

                $return_arr[$key] = $value;
            }
        }

        return $return_arr;
    }
}
