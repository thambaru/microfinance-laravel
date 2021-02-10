<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Common
{

    public static function isRoute($name)
    {
        return Str::startsWith(Route::currentRouteName(), $name);
    }
}
