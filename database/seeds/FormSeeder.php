<?php

use App\Form;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Form::truncate();
        Form::create([
            "title" => "Votaciones SUDUNT 2020",
            "description" => "Formulario para el sufragio del SUDUNT"
        ]);
    }
}
