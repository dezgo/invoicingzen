<?php

namespace App;

trait CompanyBoundary
{
    /**
     * Boot the company boundary trait for a model.
     *
     * @return void
     */
    public static function bootCompanyBoundary()
    {
        static::addGlobalScope(new CompanyBoundaryScope);
    }
}
