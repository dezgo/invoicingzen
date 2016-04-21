<?php

namespace App\Factories;

class SettingsFactory
{
    public static function create($company_id = 0)
    {
        return new \App\Services\AnlutroSettings($company_id);
    }
}
