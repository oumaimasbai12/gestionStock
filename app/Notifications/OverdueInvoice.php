<?php

namespace App\Notifications;

use App\Models\StockExit;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OverdueInvoice extends Notification
{
    use Queueable;

    public StockExit $exit;

    public function __construct(StockExit $exit)
    {
        $this->exit = $exit;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'overdue_invoice',
            'title' => 'Facture en retard',
            'message' => 'La facture #' . $this->exit->id . ' de ' . optional($this->exit->customer)->name . ' est impayée depuis plus de 7 jours.',
            'exit_id' => $this->exit->id,
            'amount_due' => $this->exit->amount_due,
            'url' => route('exits.show', $this->exit),
        ];
    }
}
