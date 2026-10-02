<?php

declare(strict_types=1);

namespace ConcurrencyShield\Tests\Common;

use Doctrine\DBAL\Connection;

class DemoDataProvider
{
    public function __construct(private Connection $connection)
    {
    }

    public function prepare(): void
    {
        $this->clear();
        $this->createSchema();
        $this->insertData();
    }

    public function getJson(): string
    {
        return file_get_contents(__DIR__ . '/../Data/expected_result.json');
    }

    private function createSchema(): void
    {
        $this->connection->executeStatement('
            CREATE TABLE "users" (
                "id" INTEGER PRIMARY KEY NOT NULL,
                "email" VARCHAR(255) NOT NULL,
                "firstName" VARCHAR(255) NOT NULL,
                "lastName" VARCHAR(255) NOT NULL
            )
        ');
        $this->connection->executeStatement('
            CREATE TABLE "groups" (
                "code" VARCHAR(10) NOT NULL,
                "name" VARCHAR(255) NOT NULL,
                "enabled" int NOT NULL,
                PRIMARY KEY ("code")
            )
        ');
        $this->connection->executeStatement('
            CREATE TABLE "permissions" (
                "code" VARCHAR(10) NOT NULL,
                "name" VARCHAR(255) NOT NULL,
                PRIMARY KEY ("code")
            )
        ');
        $this->connection->executeStatement('
            CREATE TABLE "users_groups" (
                "userId" INTEGER NOT NULL,
                "groupCode" VARCHAR(10) NOT NULL,
                FOREIGN KEY ("groupCode") REFERENCES "groups" ("code"),
                FOREIGN KEY ("userId") REFERENCES "users" ("id"),
                PRIMARY KEY ("groupCode", "userId")
            )
        ');
        $this->connection->executeStatement('
            CREATE TABLE "groups_permissions" (
                "groupCode" VARCHAR(10) NOT NULL,
                "permissionCode" VARCHAR(10) NOT NULL,
                FOREIGN KEY ("groupCode") REFERENCES "groups" ("code"),
                FOREIGN KEY ("permissionCode") REFERENCES "permissions" ("code")
            )
        ');
    }

    private function insertData(): void
    {
        $this->connection->executeStatement('
            INSERT INTO "users" ("id", "email", "firstName", "lastName")
            VALUES
                (1, \'jdvorak@example.com\', \'Jan\', \'Dvořák\'),
                (2, \'mnovak@example.com\', \'Milan\', \'Novák\'),
                (3, \'fborek@example.com\', \'František\', \'Borek\')
        ');
        $this->connection->executeStatement('
            INSERT INTO "groups" ("code", "name", "enabled")
            VALUES 
                (\'group1\', \'Group 1\', 1),
                (\'group2\', \'Group 2\', 1),
                (\'group3\', \'Group 3\', 1),
                (\'group4\', \'Group 4\', 0),
                (\'group5\', \'Group 5\', 1),
                (\'group6\', \'Group 6\', 0),
                (\'group7\', \'Group 7\', 1),
                (\'group8\', \'Group 8\', 1),
                (\'group9\', \'Group 9\', 1),
                (\'group10\', \'Group 10\', 1)
        ');
        $this->connection->executeStatement('
                INSERT INTO "permissions" ("code", "name")
                VALUES 
                    (\'permission1\', \'Permission 1\'),
                    (\'permission2\', \'Permission 2\'),
                    (\'permission3\', \'Permission 3\'),
                    (\'permission4\', \'Permission 4\'),
                    (\'permission5\', \'Permission 5\'),
                    (\'permission6\', \'Permission 6\'),
                    (\'permission7\', \'Permission 7\'),
                    (\'permission8\', \'Permission 8\'),
                    (\'permission9\', \'Permission 9\'),
                    (\'permission10\', \'Permission 10\')
        ');
        $this->connection->executeStatement('
            INSERT INTO "users_groups" ("userId", "groupCode")
            VALUES
                (1, \'group1\'),
                (1, \'group2\'),
                (1, \'group3\'),
                (1, \'group4\'),
                (1, \'group5\'),
                (1, \'group6\'),
                (1, \'group7\'),
                (1, \'group8\'),
                (1, \'group9\'),
                (1, \'group10\'),
                (2, \'group1\'),
                (2, \'group2\'),
                (2, \'group3\'),
                (2, \'group4\'),
                (2, \'group5\'),
                (2, \'group6\'),
                (2, \'group7\'),
                (2, \'group8\'),
                (2, \'group9\'),
                (2, \'group10\'),
                (3, \'group1\'),
                (3, \'group2\'),
                (3, \'group3\'),
                (3, \'group4\'),
                (3, \'group5\'),
                (3, \'group6\'),
                (3, \'group7\'),
                (3, \'group8\'),
                (3, \'group9\'),
                (3, \'group10\')
        ');
        $this->connection->executeStatement('
            INSERT INTO "groups_permissions" ("permissionCode", "groupCode")
            VALUES
                (\'permission1\', \'group1\'),
                (\'permission1\', \'group2\'),
                (\'permission1\', \'group3\'),
                (\'permission1\', \'group4\'),
                (\'permission1\', \'group5\'),
                (\'permission1\', \'group6\'),
                (\'permission1\', \'group7\'),
                (\'permission1\', \'group8\'),
                (\'permission1\', \'group9\'),
                (\'permission1\', \'group10\'),
                (\'permission2\', \'group1\'),
                (\'permission2\', \'group2\'),
                (\'permission2\', \'group3\'),
                (\'permission2\', \'group4\'),
                (\'permission2\', \'group5\'),
                (\'permission2\', \'group6\'),
                (\'permission2\', \'group7\'),
                (\'permission2\', \'group8\'),
                (\'permission2\', \'group9\'),
                (\'permission2\', \'group10\'),
                (\'permission3\', \'group1\'),
                (\'permission3\', \'group2\'),
                (\'permission3\', \'group3\'),
                (\'permission4\', \'group4\'),
                (\'permission5\', \'group5\'),
                (\'permission6\', \'group6\'),
                (\'permission7\', \'group7\'),
                (\'permission8\', \'group8\'),
                (\'permission9\', \'group9\'),
                (\'permission10\', \'group10\')
        ');
    }

    private function clear(): void
    {
        $this->connection->executeStatement('DROP TABLE IF EXISTS "users_groups"');
        $this->connection->executeStatement('DROP TABLE IF EXISTS "groups_permissions"');
        $this->connection->executeStatement('DROP TABLE IF EXISTS "groups"');
        $this->connection->executeStatement('DROP TABLE IF EXISTS "users"');
        $this->connection->executeStatement('DROP TABLE IF EXISTS "permissions"');
    }
}
