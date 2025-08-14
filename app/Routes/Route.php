<?php

namespace App\Routes;

use OutOfRangeException;

class Route {
    protected string $name;
    protected string $path;
    protected string $controller;
    protected string $handler;
    protected array $methods;

    public function __construct(array $routeInfo)
    {
        foreach ($routeInfo as $key => $value) {
            try { $this->$key = $value; }
            catch (\Exception) {
                throw new OutOfRangeException("The Route object does not have "
                    . "a settable property of " . $key);
            }
        }
    }

    public function getName() : string {
        return $this->name;
    }

    public function getPath() : string {
        return $this->path;
    }

    public function getControllerName() : string {
        return $this->controller;
    }

    public function getHandler() : string {
        return $this->handler;
    }

    public function getMethods() : array {
        return $this->methods;
    }
}
