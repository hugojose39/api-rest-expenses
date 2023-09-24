<?php

namespace Tests\Feature\Listeners\Expense\Created;

use App\Events\Expense\Created;
use App\Listeners\Expense\Created\SendCreatedExpenseNotification;
use App\Models\Expense;
use App\Models\User;
use App\Notifications\ExpenseCreated;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendCreatedExpenseNotificationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    public function test_if_listener_is_registered(): void
    {
        Event::assertListening(Created::class, SendCreatedExpenseNotification::class);
    }

    public function test_if_it_will_send_notification_properly(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $expense = Expense::factory()->for($user)->create();

        $listener = new SendCreatedExpenseNotification();

        $listener->handle(new Created($expense));

        Notification::assertSentTo($expense->user, ExpenseCreated::class);
    }
}
