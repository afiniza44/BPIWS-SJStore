<?php

namespace App\Providers;

use App\Database\NeonPostgresConnector;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

class NeonServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Replace the default pgsql connector with our Neon-aware one
        $this->app->bind('db.connector.pgsql', fn() => new NeonPostgresConnector());
    }
}
