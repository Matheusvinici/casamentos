<?php

namespace Database\Seeders;
use App\Models\Pais;
use Illuminate\Support\Facades\DB;


use Illuminate\Database\Seeder;

class PaisesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Pais::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $csvFile = fopen(base_path("/database/csvs/enderecos_paises.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Pais::create([
                    "nome" => $data['1'],
                    "codigo_ibge" => $data['2']
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
