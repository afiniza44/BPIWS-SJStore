<?php

namespace App\Database;

use Illuminate\Database\Connectors\PostgresConnector;

class NeonPostgresConnector extends PostgresConnector
{
    /**
     * Create a DSN string from a configuration.
     * Overrides parent to inject Neon endpoint via libpq `options` parameter.
     */
    protected function getDsn(array $config): string
    {
        $dsn = parent::getDsn($config);

        // Inject endpoint options for Neon SNI compatibility (older libpq)
        if (!empty($config['neon_endpoint'])) {
            $endpoint = $config['neon_endpoint'];
            // Append to DSN: options='endpoint=<id>'
            $dsn .= ";options='endpoint={$endpoint}'";
        }

        return $dsn;
    }
}
