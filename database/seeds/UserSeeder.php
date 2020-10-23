<?php

use App\Person;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $person = Person::create([
            "names" => "Javier Rodolfo",
            "last_name_pat" => "Briceño",
            "last_name_mat" => "Montaño",
            "address" => "Urb. Las Gardenias MznF Lte.23",
            "phone" => "9815598130",
            "dni" => "72764269"
        ]);

        User::create([
            "email" => "jbriceno@unitru.edu.pe",
            "password" => Hash::make("72764269"),
            "is_admin" => true,
            "person_id" => $person->id
        ]);
    }
}
