<?php

namespace Database\Seeders;
use App\Models\Cidade;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class CidadesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Cidade::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

  
        $csvFile = fopen(base_path("/database/csvs/enderecos_cidades.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Cidade::create([
                    "estado_id" => $data['1'],
                    "nome" => $data['2'],
                    "codigo_ibge" => $data['3']
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
