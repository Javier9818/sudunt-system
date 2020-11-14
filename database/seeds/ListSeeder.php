<?php

use App\Lista;
use Illuminate\Database\Seeder;

class ListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lista::create(["name" => "Lista 1"]);
        Lista::create(["name" => "Lista 2"]);
        Lista::create(["name" => "Blanco"]);
        Lista::create(["name" => "Viciado"]);
    }
}
