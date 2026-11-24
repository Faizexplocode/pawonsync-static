<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['nama' => 'Bu Diana', 'email' => 'buyer@pawonsync.test', 'role' => 'buyer'],
            ['nama' => 'Bu Ratna', 'email' => 'ratna@pawonsync.test', 'role' => 'buyer'],
            ['nama' => 'Pak Reza', 'email' => 'reza@pawonsync.test', 'role' => 'buyer'],
            ['nama' => 'Pawon Lestari', 'email' => 'seller@pawonsync.test', 'role' => 'seller'],
            ['nama' => 'Dapur Bu Sari', 'email' => 'sari@pawonsync.test', 'role' => 'seller'],
            ['nama' => 'Warung Nusa', 'email' => 'nusa@pawonsync.test', 'role' => 'seller'],
            ['nama' => 'Budi Santoso', 'email' => 'kurir@pawonsync.test', 'role' => 'kurir'],
            ['nama' => 'Agus Prasetyo', 'email' => 'agus@pawonsync.test', 'role' => 'kurir'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user + ['password' => 'password'],
            );
        }
    }
}
