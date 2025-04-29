<?php

namespace LaravelSendGrid\Facades;

use Illuminate\Support\Facades\Facade;
use SendGrid as SendGridLaravel;

class SendGrid extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SendGridLaravel::class;
    }
}
