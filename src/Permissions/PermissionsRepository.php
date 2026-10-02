<?php

declare(strict_types=1);

namespace ConcurrencyShield\Permissions;

use Generator;
use ConcurrencyShield\BaseRepository;

class PermissionsRepository extends BaseRepository
{
    /**
     * @return Generator<Permission>
     */
    public function getPermissionsByGroup(string $groupCode): Generator
    {
        return $this->hydrateAll($this->connection->fetchAllAssociative(
            'SELECT p.* FROM "permissions" p LEFT JOIN groups_permissions gp ON p.code = gp.permissionCode WHERE gp.groupCode = :groupCode',
            [
                'groupCode' => $groupCode,
            ],
        ));
    }

    protected function getEntityClass(): string
    {
        return Permission::class;
    }

}
