<?php

namespace App\Controllers;

use App\Request;
use App\Routes\Route;

class HomeController extends Controller
{
    public function __construct(Request $request, Route $route)
    {
        parent::__construct($request, $route); 
    }

    public function index() : string {
        return $this->twig->render('pages/home.html.twig');
    }
}
