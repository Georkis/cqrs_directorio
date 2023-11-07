<?php declare(strict_types=1);

namespace App\Infrastructure\Middleware;

use League\Tactician\Middleware;

class ConsoleResponseMiddleware implements Middleware
{

    public function __construct()
    {
    }

    public function execute($command, callable $next)
    {
        $next($command);
        return 0;
    }
}