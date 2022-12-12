<?php

namespace App\Services;


use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param array $data ['name' => 'string', 'email' => 'string', 'password' => 'string']
     * @return User
     */
    public function store(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    /**
     * @param User $user
     * @param array $data ['name' => 'string', 'email' => 'string', 'password' => 'string|null']
     * @return void
     */
    public function update(User $user, array $data): void
    {
        $values = [
            'name'  => $data['name'],
            'email' => $data['email'],
        ];

        // update password only password is provided
        if (isset($data['password']) && $data['password'] !== '') {
            $values['password'] = Hash::make($data['password']);
        }

        $user->update($values);
    }

    /**
     * @param User $user
     * @return void
     */
    public function destroy(User $user): void
    {
        $user->delete();
    }

    /**
     * @return User|null
     */
    public function getCurrentUser(): ?User
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return auth()->guard('web')->user();
    }
}
