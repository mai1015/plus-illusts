<?php

declare(strict_types=1);

namespace Mai1015\PlusIllusts\Web\Controllers;

class HomeController
{
    public function index()
    {
        return view('plus-illusts::welcome');
    }
}
