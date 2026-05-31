<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $products;

    public function __construct(Collection $products)
    {
        $this->products = $products;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Alerte Stock Faible - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.low-stock-alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
