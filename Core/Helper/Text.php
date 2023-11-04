<?php

namespace App\Helper;

class Text
{
    public static function excerpt($text, $limit = 60)
    {
        if (mb_strlen($text) < 60) {
            return $text;
        }
        $limit = mb_strpos($text, ' ', $limit);
        return mb_substr($text, 0, $limit) . '...';
    }
}
