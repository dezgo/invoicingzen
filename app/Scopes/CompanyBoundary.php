<?php

namespace App\Scopes;

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

    /**
	 * Get the name of the column for applying the scope.
	 *
	 * @return string
	 */
	public function getCompanyIDColumn()
	{
		return defined('static::COMPANY_ID_COLUMN') ? static::COMPANY_ID_COLUMN : 'company_id';
	}

	/**
	 * Get the fully qualified column name for applying the scope.
	 *
	 * @return string
	 */
	public function getQualifiedCompanyIDColumn()
	{
		return $this->getTable().'.'.$this->getCompanyIDColumn();
	}

	/**
	 * Get the query builder without the scope applied.
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function allCompanies()
	{
		return with(new static)->newQueryWithoutScope(new CompanyIDScope);
	}
}
