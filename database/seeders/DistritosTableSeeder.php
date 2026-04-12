<?php

namespace Database\Seeders;
use App\Models\Distrito;
use Illuminate\Support\Facades\DB;


use Illuminate\Database\Seeder;

class DistritosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Distrito::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $csvFile = fopen(base_path("/database/csvs/enderecos_distritos.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Distrito::create([
                    "cidade_id" => $data['1'],
                    "nome" => $data['2'],
                    "codigo_ibge" => $data['3']
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
