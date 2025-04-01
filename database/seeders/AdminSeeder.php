<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Insertar usuario administrador
        $userId = DB::table('users')->insertGetId([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('GATEE1234'),
            'email_verified_at' => now(),
            'activate' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Asignar el rol de administrador (role_id = 1)
        DB::table('role_user')->insert([
            'role_id' => 1,
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Insertar datos en la tabla 'people'
        DB::table('people')->insert([
            'name' => 'Carlos',
            'last_name' => 'Centeno',
            'birth_date' => '1990-05-15',
            'phone' => '871-122-2311',
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
