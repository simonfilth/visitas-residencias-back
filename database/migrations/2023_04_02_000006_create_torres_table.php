<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTorresTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'torres';

    /**
     * Run the migrations.
     * @table torres
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('residencia_id');
            $table->string('nombre', 100);
            $table->timestamps();

            $table->index(["residencia_id"], 'torres_usuario_id_foreign_idx');


            $table->foreign('residencia_id', 'torres_usuario_id_foreign_idx')
                ->references('id')->on('residencias')
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
