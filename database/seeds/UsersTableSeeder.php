<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $user = [
            'id'             => 1,
            'name'           => 'Admin',
            'email'          => 'admin@admin.com',
            'password'       => '$2y$10$BCjxgVotWSy3UM/uHCYYJ.4knu/QJgyJ3.eS6BLN12Xg.ElohtJ/G',
            'remember_token' => null,
        ];

        User::create($user);
    }
}
