<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface Settings
{
    public function set($key, $value);

    public function get($key, $default = null);

    public function setAllUsing(Request $request);
}
