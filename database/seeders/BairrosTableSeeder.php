<?php

namespace Database\Seeders;
use App\Models\Bairro;
use Illuminate\Support\Facades\DB;


use Illuminate\Database\Seeder;

class BairrosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Bairro::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $csvFile = fopen(base_path("/database/csvs/enderecos_bairros.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Bairro::create([
                    "nome" => $data['1'],
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
