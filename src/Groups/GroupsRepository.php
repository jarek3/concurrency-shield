<?php

declare(strict_types=1);

namespace ConcurrencyShield\Groups;

use ConcurrencyShield\BaseRepository;
use ConcurrencyShield\Permissions\Permission;
use ConcurrencyShield\Permissions\PermissionsRepository;

class GroupsRepository extends BaseRepository
{
    /**
     * @return \Generator<Group>
     */
    public function getGroupsByUserId(int $userId): \Generator
    {
        return $this->hydrateAll($this->connection->fetchAllAssociative(
            'SELECT g.* FROM "groups" g LEFT JOIN users_groups ug ON g.code = ug.groupCode WHERE ug."userId" = :userId AND g.enabled = :enabled',
            [
                'userId' => $userId,
                'enabled' => 1,
            ],
        ));
    }

    /**
     * @return \Generator<Group>
     */
    public function getByPermissionCode(string $permissionCode): \Generator
    {
        return $this->hydrateAll($this->connection->fetchAllAssociative(
            'SELECT g.* FROM "groups" g JOIN "groups_permissions" pg ON pg.groupCode=g.code WHERE pg.permissionCode = :permissionCode AND g.enabled = :enabled',
            [
                'permissionCode' => $permissionCode,
                'enabled' => 1,
            ],
        ));
    }

    protected function getEntityClass(): string
    {
        return Group::class;
    }


    /**
     * @return \Generator<Permission>
     */
    public function getPermissions(string $code): \Generator
    {
        return (new PermissionsRepository($this->connection))->getPermissionsByGroup($code);
    }
}
