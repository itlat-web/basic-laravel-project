<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // delete all users
        User::query()->delete();

        $this->userService->store([
            'name'     => 'John Smith',
            'email'    => 'john.smith@gmail.com',
            'password' => '12345678',
        ]);
    }
}
