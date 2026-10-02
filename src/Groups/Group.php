<?php

declare(strict_types=1);

namespace ConcurrencyShield\Groups;

class Group
{
    public function __construct(
        private readonly GroupsRepository $groupsRepository,
        private readonly string $code,
        private readonly string $name,
        private readonly int $enabled,
    ) {
    }

    public function toArray()
    {
        $permissions = [];
        foreach ($this->groupsRepository->getPermissions($this->code) as $permission) {
            $permissions[] = $permission->toArray();
        }
        return [
            'code' => $this->code,
            'name' => $this->name,
            'permissions' => $permissions,
        ];
    }
}
