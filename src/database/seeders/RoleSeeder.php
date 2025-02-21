<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $roles = [
                [
                    'name' => 'admin',
                    'display_name' => '管理者',
                    'description' => 'システム管理者'
                ],
                [
                    'name' => 'shop_owner',
                    'display_name' => '店舗代表者',
                    'description' => '店舗情報の管理と予約管理が可能'
                ],
                [
                    'name' => 'user',
                    'display_name' => '一般ユーザー',
                    'description' => '予約とレビューが可能'
                ]
            ];

            foreach ($roles as $role) {
                Role::firstOrCreate([
                    'name' => $role['name']
                ], $role);
            }

            $admin = User::firstOrCreate(
                [
                    'email' => 'admin@example.com'
                ],
                [
                    'name' => 'Admin User',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now()
                ]
            );

            $adminRole = Role::where('name', 'admin')->first();
            if (!$admin->hasRole('admin')) {
                $admin->roles()->attach($adminRole->id);
            }
        });
    }
}
