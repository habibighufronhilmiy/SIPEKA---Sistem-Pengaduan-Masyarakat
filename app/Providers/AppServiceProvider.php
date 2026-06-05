<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Mail::extend('brevo', function (array $config) {
            $factory = new BrevoTransportFactory(
                dispatcher: $this->app->make(\Symfony\Component\EventDispatcher\EventDispatcherInterface::class),
                logger: $this->app->make(\Psr\Log\LoggerInterface::class),
            );

            return $factory->create(Dsn::fromString($config['url']));
        });
    }
}
