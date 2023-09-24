<?php

namespace Tests\Feature\Notifications;

use App\Models\Expense;
use App\Models\User;
use App\Notifications\ExpenseCreated;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ExpenseCreatedTest extends TestCase
{
    use LazilyRefreshDatabase;

    private User $user;
    private Expense $expense;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
        Notification::fake();

        $this->user = User::factory()->create();

        $this->expense = Expense::factory()->for($this->user)->create();

        app()->setLocale('pt-BR');
    }

    public function test_if_only_sends_notification_to_mail(): void
    {
        $notification = new ExpenseCreated($this->expense);

        $channels = $notification->via();

        $this->assertCount(1, $channels);
        $this->assertEquals('mail', $channels[0]);
    }

    public function test_if_will_generate_a_correct_message(): void
    {
        $notification = new ExpenseCreated($this->expense);

        $mailMessage = $notification->toMail();

        $this->assertEquals('Despesa cadastrada!', $mailMessage->subject);
        $this->assertEquals($this->expense->user->name.', tudo bem?', $mailMessage->greeting);
        $this->assertEquals('Sua despesa foi cadastrada com sucesso.', $mailMessage->introLines[0]);
        $this->assertEquals('Informações da despesa:', $mailMessage->introLines[2]);
        $this->assertEquals("Descrição: {$this->expense->description}", $mailMessage->introLines[3]);
        $this->assertEquals('Data: '.Carbon::parse($this->expense->date)->format('d/m/Y H:i:s'), $mailMessage->introLines[4]);
        $this->assertEquals("Valor: {$this->expense->value}", $mailMessage->introLines[5]);
    }
}
