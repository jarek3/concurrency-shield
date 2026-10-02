<?php

declare(strict_types=1);

namespace ConcurrencyShield;;

use Doctrine\DBAL\Connection;

abstract class BaseRepository
{
    public function __construct(protected readonly Connection $connection) {
    }

    /**
     * @param array<array> $data
     *
     * @return \Generator<object>
     */
    protected function hydrateAll(array $data): \Generator
    {
        foreach ($data as $row) {
            yield $this->hydrate($row);
        }
    }

    protected function hydrate(array $row)
    {
        $class = $this->getEntityClass();

        return new $class($this, ...$row);
    }

    /**
     * @return class-string
     */
    abstract protected function getEntityClass(): string;
}
