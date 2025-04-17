<?php

namespace YourVendor\SendGridLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class SendGrid extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \SendGrid::class;
    }
}
