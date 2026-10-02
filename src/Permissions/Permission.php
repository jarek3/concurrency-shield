<?php

declare(strict_types=1);

namespace ConcurrencyShield\Permissions;

class Permission
{
    public function __construct(
        private readonly PermissionsRepository $permissionsRepository,
        private readonly string $code,
        private readonly string $name,
    ) {
    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
        ];
    }
}
