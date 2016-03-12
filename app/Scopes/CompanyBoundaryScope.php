<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Company;

class CompanyBoundaryScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $model = $builder->getModel();
        $builder->where($model->getQualifiedCompanyIDColumn(), '=', Company::my_id());
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function remove(Builder $builder)
    {
        $column = $builder->getModel()->getQualifiedCompanyIDColumn();

        $query = $builder->getQuery();

        foreach ((array) $query->wheres as $key => $where)
        {
            // If the where clause is a soft delete date constraint, we will remove it from
            // the query and reset the keys on the wheres. This allows this developer to
            // include deleted model in a relationship result set that is lazy loaded.
            if ($this->isCompanyIDConstraint($where, $column))
            {
                unset($query->wheres[$key]);

                $query->wheres = array_values($query->wheres);
            }
        }
    }
}
