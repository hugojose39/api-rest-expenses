<?php

namespace App\Events\Expense;

use App\Models\Expense;

class Created
{
    public function __construct(public Expense $expense)
    {
    }
}
