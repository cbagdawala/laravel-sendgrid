<?php

namespace YourVendor\SendGridLaravel;

use Illuminate\Support\ServiceProvider;
use SendGrid;

class SendGridServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/sendgrid.php', 'sendgrid');

        $this->app->singleton(SendGrid::class, function ($app) {
            return new SendGrid(config('sendgrid.api_key'));
        });

        $this->app->alias(SendGrid::class, 'sendgrid');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/sendgrid.php' => config_path('sendgrid.php'),
        ], 'config');
    }
}
