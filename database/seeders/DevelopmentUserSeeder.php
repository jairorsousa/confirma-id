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
            'cpf' => '12345678900',
            'phone' => '11999990000',
        ],
        [
            'name' => 'Parceiro ConfirmaID',
            'email' => 'partner@confirmaid.local',
            'role' => 'partner',
            'cpf' => '22345678900',
            'phone' => '11999990001',
        ],
        [
            'name' => 'Admin ConfirmaID',
            'email' => 'admin@confirmaid.local',
            'role' => 'admin',
            'cpf' => '32345678900',
            'phone' => '11999990002',
        ],
        [
            'name' => 'Super Admin ConfirmaID',
            'email' => 'superadmin@confirmaid.local',
            'role' => 'super_admin',
            'cpf' => '42345678900',
            'phone' => '11999990003',
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
                    'account_status' => 'active',
                ],
            );

            $user->syncRoles([$seedUser['role']]);

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $seedUser['name'],
                    'cpf' => $seedUser['cpf'],
                    'birth_date' => '1990-01-01',
                    'phone' => $seedUser['phone'],
                ],
            );
        }
    }
}
