<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoApiTransport;

class MailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Mail::extend('brevo', function (array $config) {
            $key = $config['key'] ?? config('services.brevo.key');
            return new BrevoApiTransport($key);
        });
    }
}
