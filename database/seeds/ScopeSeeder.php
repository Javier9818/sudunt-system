<?php

use App\Scope;
use Illuminate\Database\Seeder;

class ScopeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Scope::truncate();
        Scope::create(["description" => 'Administrador']);
        Scope::create(["description" => 'Usuario']); 
    }
}
