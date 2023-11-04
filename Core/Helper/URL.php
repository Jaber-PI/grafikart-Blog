<?php

namespace App\Helper;

use Exception;

class URL
{
    public static function getInt($name, $default = null): ?int
    {
        $value = $_GET[$name] ?? $default;
        if (!filter_var($value, FILTER_VALIDATE_INT) || $value <= 0) {
            throw new Exception("$name has a non valid value :" . $value);
        }
        return (int) $value;
    }
}
