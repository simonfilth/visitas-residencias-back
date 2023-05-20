<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApartamentosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'apartamentos';

    /**
     * Run the migrations.
     * @table apartamentos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('torre_id');
            $table->string('nombre', 50);
            $table->timestamps();

            $table->index(["torre_id"], 'apartamentos_torre_id_foreign_idx');


            $table->foreign('torre_id', 'apartamentos_torre_id_foreign_idx')
                ->references('id')->on('torres')
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
