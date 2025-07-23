<?php

namespace App\Http\Repositories\Users;

use App\Models\User;

class UsersRepository
{
    /**
     * @param array $fields
     * @return User
     */
    public static function create(array $fields): User
    {
        return User::query()->create($fields);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public static function getByEmail(string $email): User|null
    {
        return User::query()
            ->select(
                'id',
                'password',
                'name',
                'email_verified_at'
            )
            ->where('email', '=', $email)
            ->first();
    }

}
