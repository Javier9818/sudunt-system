<?php

use App\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Teacher::create([
            "code" => "4488",
            "email" => "voto@unitru.edu.pe",
            "names" => "Juan Carlos",
            "last_names" => "Melendez Cabrera",
            "token" => preg_replace("/\//i", "online", Hash::make("4488"))
        ]);

        Teacher::create([
            "code" => "4478",
            "email" => "voto2@unitru.edu.pe",
            "names" => "Juan Carlos",
            "last_names" => "Melendez Cabrera",
            "token" => preg_replace("/\//i", "online", Hash::make("4478"))
        ]);

        Teacher::create([
            "code" => "4468",
            "email" => "voto3@unitru.edu.pe",
            "names" => "Juan Carlos",
            "last_names" => "Melendez Cabrera",
            "token" => preg_replace("/\//i", "online", Hash::make("4468"))
        ]);

    }
}
