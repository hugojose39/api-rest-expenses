<?php

namespace App\Notifications;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseCreated extends Notification
{
    public function __construct(private readonly Expense $expense)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->greeting(__($this->expense->user->name.', tudo bem?'))
            ->subject(__('Despesa cadastrada!'))
            ->line(__('Sua despesa foi cadastrada com sucesso.'))
            ->line('---')
            ->line(__('Informações da despesa:'))
            ->line(__("Descrição: {$this->expense->description}"))
            ->line(__('Data: '.Carbon::parse($this->expense->date)->format('d/m/Y H:i:s')))
            ->line(__("Valor: {$this->expense->value}"))
            ->line('---');
    }
}
