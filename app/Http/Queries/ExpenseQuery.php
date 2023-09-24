<?php

namespace App\Http\Queries;

use App\Models\Expense;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ExpenseQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Expense::query());

        $this->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('user_id'),
        ]);

        $this->allowedSorts([
            'id',
            'created_at',
        ]);

        $this->defaultSorts('-id');
    }
}
