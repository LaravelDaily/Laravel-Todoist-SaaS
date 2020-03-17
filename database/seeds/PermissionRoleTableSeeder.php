<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $adminPermissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($adminPermissions->pluck('id'));

        // Premium user
        $premiumUserPermissions = $adminPermissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) != 'user_'
                && substr($permission->title, 0, 5) != 'role_'
                && substr($permission->title, 0, 11) != 'permission_'
                && $permission->title != 'dashboard';
        });
        Role::findOrFail(3)->permissions()->sync($premiumUserPermissions);

        // Free user
        $freeUserPermissions = $premiumUserPermissions->filter(function ($permission) {
            return $permission->title != 'reminders' && $permission->title != 'comments' && $permission->title != 'labels';
        });
        Role::findOrFail(2)->permissions()->sync($freeUserPermissions);

        // Allow only 80 projects for free
        $permission = Permission::where('title', 'project_create')->first();
        if ($permission) {
            \DB::table('permission_role')
                ->where('permission_id', $permission->id)
                ->where('role_id', 2)
                ->update(['max_amount' => 80]);
        }
    }
}
