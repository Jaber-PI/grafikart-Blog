<?php

namespace App;

class RouterHelper
{

    public static function view(string $view)
    {
        require dirname(__DIR__) . '/views/' . $view;
    }
}
