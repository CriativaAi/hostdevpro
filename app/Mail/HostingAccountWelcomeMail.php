<?php

namespace App\Mail;

use App\Models\Client;
use App\Models\HostingAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HostingAccountWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public Client $client;
    public HostingAccount $account;
    public string $plainPassword;
    public array $dnsDetails;
    public array $serverDetails;

    /**
     * Create a new message instance.
     */
    public function __construct(
        Client $client,
        HostingAccount $account,
        string $plainPassword,
        array $dnsDetails = [],
        array $serverDetails = []
    ) {
        $this->client = $client;
        $this->account = $account;
        $this->plainPassword = $plainPassword;
        $this->dnsDetails = $dnsDetails;
        $this->serverDetails = $serverDetails;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address', 'hdp@hostdevpro.app.br'),
                config('mail.from.name', 'HostDevPro Cloud')
            ),
            subject: '🚀 Sua Hospedagem HostDevPro Foi Ativada! Dados de Acesso e DNS de ' . $this->account->domain,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.hosting_welcome',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
