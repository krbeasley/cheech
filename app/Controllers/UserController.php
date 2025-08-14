<?php

namespace App\Controllers;

use App\Request;
use App\Routes\Route;

class UserController extends Controller {
    public function __construct(Request $request, Route $route)
    {
        parent::__construct($request, $route);
    }

    public function view() : string {
        if (!array_key_exists("user_id", $this->path_vars)) {
            return $this->error404();
        }
        $user_id = $this->path_vars['user_id'];

        return "Hello from the user view page. User Id is: $user_id";
    }

    public function error404() : string {
        return "Error 404 Page Not Found";
    }
}
