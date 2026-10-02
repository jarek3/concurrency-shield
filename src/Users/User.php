<?php

declare(strict_types=1);

namespace ConcurrencyShield\Users;

class User
{
    public function __construct(
        private readonly UsersRepository $usersRepository,
        protected int $id,
        protected string $email,
        protected string $firstName,
        protected string $lastName,
    ) {
    }

    public function toArray()
    {
        $permissionGroups = [];
        foreach ($this->usersRepository->getUserGroups($this->id) as $group) {
            $permissionGroups[] = $group->toArray();
        }
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'permissionGroups' => $permissionGroups,
        ];
    }
}
