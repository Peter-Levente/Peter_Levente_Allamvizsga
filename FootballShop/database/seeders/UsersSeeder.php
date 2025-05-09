<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            ['id' => 1, 'email' => 'plevente202@gmail.com', 'password' => '$2y$12$dt2FL7pYJDL1imPho9KP4enqMjOxV2kPofE5CUYoxm0wVcDcXHFi6', 'remember_token' => 'VgEJsboQIi5Vjl9VXq17jpgUOyiCzf9C6FEo1JfRYvfGLOqYwCK0EiTMCnPk', 'name' => 'Levente Péter', 'created_at' => '2025-03-07 08:17:15', 'updated_at' => '2025-03-07 08:17:15'],
            ['id' => 2, 'email' => 'peterglevente@uni.sapientia.ro', 'password' => '$2y$12$BuPJbaHW4ljyo8R8SiHn0eubRwgSqj9ipyCs5eZ85zmIxM8p/1Dva', 'remember_token' => 'fiYylXA8ZCZsHxO026ButHUgiQnvPiemz2TQYbg4VSeRBf9fFYCkG9GnAB6z', 'name' => 'Great Dane', 'created_at' => '2025-03-12 15:23:40', 'updated_at' => '2025-03-12 15:23:40'],
            ['id' => 3, 'email' => 'koli@camelcoding.com', 'password' => '$2y$12$Moy1lF2LNFbORJHek2WZ3OBrnV28Bv9jGJ7NAQHK5v7nNKjbLqpxa', 'remember_token' => null, 'name' => 'Dark Cry', 'created_at' => '2025-03-12 15:30:40', 'updated_at' => '2025-03-12 15:30:40'],
            ['id' => 4, 'email' => 'peterg@gmail.ro', 'password' => '$2y$12$OFR6OuwGLE8lGfXOk1P8Ce0tSG8siHZrTvPrweJjdfMnt1jJvgPTO', 'remember_token' => null, 'name' => 'Peter Gabor', 'created_at' => '2025-03-12 15:32:56', 'updated_at' => '2025-03-12 15:32:56']
        ]);
    }
}
