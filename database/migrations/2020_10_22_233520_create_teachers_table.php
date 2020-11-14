<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('correo_institucional', 100)->nullable();
            $table->string('correo_personal', 100)->nullable();
            $table->char('sexo', 1);
            $table->string('facultad');
            $table->string('departamento');
            $table->string('categoria');
            $table->string('nombres');
            $table->string('token')->nullable();
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
