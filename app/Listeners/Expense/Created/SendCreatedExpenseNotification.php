<?php

namespace App\Listeners\Expense\Created;

use App\Events\Expense\Created;
use App\Notifications\ExpenseCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCreatedExpenseNotification implements ShouldQueue
{
    public function handle(Created $event): void
    {
        $expense = $event->expense;

        $expense->user->notify(new ExpenseCreated($expense));
    }
}
