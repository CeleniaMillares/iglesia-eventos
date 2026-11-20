<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            MiembroSeeder::class,
            EventoSeeder::class,
            ConvocatoriaSeeder::class,
            ConfirmacionSeeder::class,
            ActaSeeder::class,
        ]);
    }
}
