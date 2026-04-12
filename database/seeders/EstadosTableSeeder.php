<?php

namespace Database\Seeders;
use App\Models\Estado;
use Illuminate\Support\Facades\DB;


use Illuminate\Database\Seeder;

class EstadosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Estado::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $csvFile = fopen(base_path("/database/csvs/enderecos_estados.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Estado::create([
                    "pais_id" => $data['0'],
                    "nome" => $data['1'],
                    "sigla" => $data['2'],
                    "codigo_ibge" => $data['3']
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
