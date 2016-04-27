<?php

namespace App;

class Money
{
    public static function get($amount)
    {
        return round($amount,2);
    }

    public static function getFormatted($amount)
    {
        return number_format($amount,2);
    }
}
