<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevelopmentUserSeeder extends Seeder
{
    /**
     * @var array<int, array{name: string, email: string, role: string}>
     */
    private array $users = [
        [
            'name' => 'Usuario ConfirmaID',
            'email' => 'user@confirmaid.local',
            'role' => 'user',
        ],
        [
            'name' => 'Parceiro ConfirmaID',
            'email' => 'partner@confirmaid.local',
            'role' => 'partner',
        ],
        [
            'name' => 'Admin ConfirmaID',
            'email' => 'admin@confirmaid.local',
            'role' => 'admin',
        ],
        [
            'name' => 'Super Admin ConfirmaID',
            'email' => 'superadmin@confirmaid.local',
            'role' => 'super_admin',
        ],
    ];

    public function run(): void
    {
        foreach ($this->users as $seedUser) {
            $user = User::updateOrCreate(
                ['email' => $seedUser['email']],
                [
                    'name' => $seedUser['name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ],
            );

            $user->syncRoles([$seedUser['role']]);
        }
    }
}
