<?php

namespace LaravelSendGrid;

use SendGrid;

class SendGridManager
{
    protected $client;

    public function __construct(SendGrid $client)
    {
        $this->client = $client;
    }

    public function send($email)
    {
        return $this->client->send($email);
    }
}
