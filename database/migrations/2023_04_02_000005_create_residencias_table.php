<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidenciasTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'residencias';

    /**
     * Run the migrations.
     * @table residencias
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->string('nombre_residencia', 100);
            $table->string('nit', 20);
            $table->string('pin', 4);
            $table->string('foto')->nullable();
            $table->timestamps();

            $table->index(["usuario_id"], 'residencias_usuario_id_foreign_idx');


            $table->foreign('usuario_id', 'residencias_usuario_id_foreign_idx')
                ->references('id')->on('users')
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
