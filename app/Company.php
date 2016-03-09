<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CompanyBoundary;

class Company extends Model
{
    use CompanyBoundary;
    
    protected $table = 'companies';

}
