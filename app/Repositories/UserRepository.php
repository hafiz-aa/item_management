<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    protected function model(): string
    {
        return User::class;
    }

    public function createWithRoles(array $data, array $roles): User
    {
        $user = $this->model->create($data);
        $user->assignRole($roles);
        return $user;
    }

    public function updateWithRoles(User $user, array $data, array $roles): bool
    {
        $updated = $this->update($user, $data);
        if ($updated) {
            $user->syncRoles($roles);
        }
        return $updated;
    }
}
