<?php

namespace App;

use \Carbon\Carbon;

class ZenDateTime
{
    const LONG = 'd-m-Y H:i:s';

    public static function formatLong($datetime)
    {
        switch (gettype($datetime)) {
            case 'integer':
                return date(self::LONG,$datetime);
                break;
            case 'object':
                if ($datetiem instanceof Carbon)
                return $datetime->format(self::LONG);
                break;
            default:
                throw new \Exception('Unrecognised type of datetime - '.$datetime);
                break;
        }
    }
}
