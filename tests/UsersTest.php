<?php

declare(strict_types=1);

namespace ConcurrencyShield\Tests;

use Doctrine\DBAL\Connection;
use ConcurrencyShield\Tests\Common\DbProvider;
use ConcurrencyShield\Tests\Common\DemoDataProvider;
use ConcurrencyShield\Tests\Common\SqlLogger;
use ConcurrencyShield\Users\UsersRepository;
use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    private Connection $connection;

    private SqlLogger $sqlLogger;

    private DemoDataProvider $demoDataProvider;

    protected function setUp(): void
    {
        $this->sqlLogger = new SqlLogger();
        $this->connection = DbProvider::getConnection($this->sqlLogger);
        $this->demoDataProvider = new DemoDataProvider($this->connection);
        $this->demoDataProvider->prepare();
        $this->sqlLogger->reset();
    }

    public function testGetUsersApiResponse(): void
    {
        $usersRepository = new UsersRepository($this->connection);
        $response = $usersRepository->getUsersApiResponse();

        // 🔥 ODSTRANĚNÍ WINDOWS KONCŮ ŘÁDKŮ (CRLF -> LF) PŘED POROVNÁNÍM
        $expectedJson = str_replace("\r\n", "\n", trim($this->demoDataProvider->getJson()));
        $actualJson = str_replace("\r\n", "\n", trim(json_encode($response, flags: JSON_PRETTY_PRINT)));

        $this->assertSame($expectedJson, $actualJson);

        $this->assertLessThan(29, count($this->sqlLogger->getQueries()));
    }
}
