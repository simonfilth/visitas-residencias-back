<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropietariosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'propietarios';

    /**
     * Run the migrations.
     * @table propietarios
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('apartamento_id'); //revisar creo que no seria eso
            $table->string('nombre', 100);
            $table->string('email', 100);
            $table->timestamps();

            $table->index(["apartamento_id"], 'propietarios_apartamento_id_foreign_idx');


            $table->foreign('apartamento_id', 'propietarios_apartamento_id_foreign_idx')
                ->references('id')->on('apartamentos')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
