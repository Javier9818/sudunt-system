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
            "correo_personal" => "jbriceno@unitru.edu.pe",
            "correo_institucional" => "jbriceno@unitru.edu.pe",
            "nombres" => "Juan Carlos Melendez Cabrera",
            "sexo" => "M",
            "departamento" => "Desconocido",
            "facultad" => "Desconocido",
            "categoria" => "Desconocido",
            "token" => preg_replace("/\//i", "online", Hash::make("4488"))
        ]);

        Teacher::create([
            "code" => "4478",
            "correo_personal" => "jbricenomontano@gmail.com",
            "correo_institucional" => "jbricenomontano@gmail.com",
            "nombres" => "Javier Briceño Montaño",
            "sexo" => "M",
            "departamento" => "Desconocido",
            "facultad" => "Desconocido",
            "categoria" => "Desconocido",
            "token" => preg_replace("/\//i", "online", Hash::make("4478"))
        ]);

        Teacher::create([
            "code" => "4468",
            "correo_personal" => "xxrodolfoxx98@gmail.com",
            "correo_institucional" => "xxrodolfoxx98@gmail.com",
            "nombres" => "Javier Briceño Montaño",
            "sexo" => "M",
            "departamento" => "Desconocido",
            "facultad" => "Desconocido",
            "categoria" => "Desconocido",
            "token" => preg_replace("/\//i", "online", Hash::make("4468"))
        ]);

    }
}
