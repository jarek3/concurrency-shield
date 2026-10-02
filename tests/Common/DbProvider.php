<?php

namespace ConcurrencyShield\Tests\Common;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\Middleware;
use Doctrine\DBAL\Tools\DsnParser;
use Psr\Log\LoggerInterface;

class DbProvider
{
    public static function getConnection(LoggerInterface $sqlLogger): Connection
    {
        return DriverManager::getConnection(
            (new DsnParser())->parse('pdo-sqlite:///' . __DIR__ . '/../Data/db.sqlite'),
            (new Configuration())->setMiddlewares([new Middleware($sqlLogger)]),
        );
    }
}
