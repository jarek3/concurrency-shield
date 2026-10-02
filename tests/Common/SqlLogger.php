<?php

declare(strict_types=1);

namespace ConcurrencyShield\Tests\Common;

use Psr\Log\LoggerInterface;

class SqlLogger implements LoggerInterface
{

    private array $queries = [];

    public function getQueries(): array
    {
        return $this->queries;
    }
    public function reset(): void
    {
        $this->queries = [];
    }

    public function emergency(\Stringable|string $message, array $context = []): void
    {
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        if (array_key_exists('sql', $context) !== false) {
            $message = str_replace('{sql}', $context['sql'], $message);
        }
        if (array_key_exists('params', $context) !== false) {
            $message = str_replace('{params}', implode(', ', $context['params']), $message);
        }
        if (array_key_exists('types', $context) !== false) {
            $message = str_replace('{types}', implode(', ', $context['types']), $message);
        }

        $this->queries[] = $message;
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
    }
}
