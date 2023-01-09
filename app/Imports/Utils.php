<?php

namespace App\Imports;

class Utils 
{
    public static function cleanString($input)
    {
        return ucwords(utf8_encode(trim(utf8_decode($input), " \t\n\r\0\x0B\xA0")));
    }
}
