<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    public static function my_id()
    {
        if (\Auth::check()) {
            return \Auth::user()->company_id;
        }
        else {
            return 1;
        }
    }
}
