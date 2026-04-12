<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {

        // Chama o seeder das Escolas
        $this->call(EscolasTableSeeder::class);


        // Chama o seeder das Escolas
        $this->call(BairrosTableSeeder::class);


        // Chama o seeder das Escolas
        $this->call(PaisesTableSeeder::class);

        // Chama o seeder das Escolas
        $this->call(EstadosTableSeeder::class);

           // Chama o seeder das Escolas
        $this->call(CidadesTableSeeder::class);

        $this->call(CreateAdminUserSeeder::class);

      $this->call(PermissionTableSeeder::class);


          
    }
}
