<?php

namespace LaravelSendGrid;

use Illuminate\Mail\MailManager;
use Illuminate\Mail\Transport\Transport;
use Illuminate\Support\ServiceProvider;
use SendGrid;
use SendGrid\Mail\Mail as SendGridMail;
use Swift_Mime_SimpleMessage;

class SendGridServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/sendgrid.php', 'sendgrid');

        $this->app->singleton(SendGrid::class, function () {
            return new SendGrid(config('sendgrid.api_key'));
        });

        $this->app->alias(SendGrid::class, 'sendgrid');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/sendgrid.php' => config_path('sendgrid.php'),
        ], 'config');

        // Register custom transport
        $this->app->make(MailManager::class)->extend('sendgrid', function ($config) {
            return new class(app(SendGrid::class)) extends Transport {
                protected $client;

                public function __construct(SendGrid $client)
                {
                    $this->client = $client;
                }

                public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
                {
                    $email = new SendGridMail();
                    $from = $message->getFrom();
                    $email->setFrom(array_key_first($from), $from[array_key_first($from)]);

                    $email->setSubject($message->getSubject());

                    foreach ((array)$message->getTo() as $address => $name) {
                        $email->addTo($address, $name);
                    }

                    $body = $message->getBody();
                    $contentType = $message->getContentType();

                    $email->addContent($contentType, $body);

                    return $this->client->send($email);
                }
            };
        });
    }
}
