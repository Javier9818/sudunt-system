<?php

use App\Person;
use App\ScopeUser;
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
        Person::truncate();
        ScopeUser::truncate();
        
        $person = Person::create([
            "names" => "Javier Rodolfo",
            "last_names" => "Briceño Montaño",
            "address" => "Urb. Las Gardenias MznF Lte.23",
            "phone" => "9815598130",
            "dni" => "72764269"
        ]);

        $user = User::create([
            "email" => "jbriceno@unitru.edu.pe",
            "password" => Hash::make("72764269"),
            "is_admin" => true,
            "person_id" => $person->id
        ]);

        ScopeUser::create([
            "user_id" => $user->id,
            "scope_id" => 1
        ]);
    }
}
