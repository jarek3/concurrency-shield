<?php

declare(strict_types=1);

namespace ConcurrencyShield\Users;

use ConcurrencyShield\BaseRepository;
use ConcurrencyShield\Groups\Group;
use ConcurrencyShield\Groups\GroupsRepository;

class UsersRepository extends BaseRepository
{
    public function getUsersApiResponse(): array
    {
        $sql = '
            SELECT 
                u.id, 
                u.email, 
                u.firstName, 
                u.lastName,
                g.code AS group_code, 
                g.name AS group_name,
                p.code AS permission_code,
                p.name AS permission_name
            FROM users u
            LEFT JOIN users_groups ug ON u.id = ug."userId"
            LEFT JOIN "groups" g ON ug.groupCode = g.code AND g.enabled = 1
            LEFT JOIN groups_permissions gp ON g.code = gp.groupCode
            LEFT JOIN permissions p ON gp.permissionCode = p.code
            ORDER BY u.id ASC
        ';

        $rows = $this->connection->fetchAllAssociative($sql);
        $users = [];

        foreach ($rows as $row) {
            $userId = $row['id'];
            if (!isset($users[$userId])) {
                $users[$userId] = [
                    'id' => (int)$row['id'],
                    'email' => $row['email'],
                    'firstName' => $row['firstName'],
                    'lastName' => $row['lastName'],
                    'permissionGroups' => [],
                ];
            }

            if ($row['group_code']) {
                $groupCode = $row['group_code'];

                if (!isset($users[$userId]['permissionGroups'][$groupCode])) {
                    $users[$userId]['permissionGroups'][$groupCode] = [
                        'code' => $row['group_code'],
                        'name' => $row['group_name'],
                        'permissions' => [],
                    ];
                }

                if ($row['permission_code']) {
                    $permCode = $row['permission_code'];
                    if (!isset($users[$userId]['permissionGroups'][$groupCode]['permissions'][$permCode])) {
                        $users[$userId]['permissionGroups'][$groupCode]['permissions'][$permCode] = [
                            'code' => $row['permission_code'],
                            'name' => $row['permission_name'],
                        ];
                    }
                }
            }
        }

        return array_values(array_map(function ($user) {
            // 1. Natural sort permission groups by their code key (group1, group2 ... group10)
            uksort($user['permissionGroups'], 'strnatcmp');

            $user['permissionGroups'] = array_values(array_map(function ($group) {
                // 2. Natural sort permissions inside each group by their code
                usort($group['permissions'], function ($a, $b) {
                    return strnatcmp($a['code'], $b['code']);
                });
                return $group;
            }, $user['permissionGroups']));

            return $user;
        }, $users));
    }

    /**
     * @return \Generator<User>
     */
    public function getUsers(): \Generator
    {
        return $this->hydrateAll(
            $this->connection->fetchAllAssociative('SELECT * FROM "users"')
        );
    }

    protected function getEntityClass(): string
    {
        return User::class;
    }

    /**
     * @return \Generator<Group>
     */
    public function getUserGroups(int $userId): \Generator
    {
        return (new GroupsRepository($this->connection))->getGroupsByUserId($userId);
    }
}
