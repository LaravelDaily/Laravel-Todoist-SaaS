<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'    => 1,
                'title' => 'Admin',
                'stripe_plan_id' => NULL,
                'price' => NULL,
            ],
            [
                'id'    => 2,
                'title' => 'Free Plan',
                'stripe_plan_id' => NULL,
                'price' => NULL,
            ],
            [
                'id'    => 3,
                'title' => 'Premium Plan',
                'stripe_plan_id' => 'plan_xxxxxxxxx',
                'price' => 300,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
