<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitasTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'visitas';

    /**
     * Run the migrations.
     * @table visitas
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->string('torre', 100)->nullable();
            $table->string('apartamento', 100)->nullable();
            $table->string('propietario', 100)->nullable();
            $table->unsignedInteger('arl_id');
            $table->unsignedInteger('tipo_sangre_id');
            $table->unsignedInteger('eps_id');
            $table->dateTime('hora_ingreso');
            $table->string('visitante_nombre', 100);
            $table->string('visitante_foto', 100);
            $table->string('observacion')->nullable();
            $table->timestamps();

            $table->index(["usuario_id"], 'visitas_usuario_id_foreign_idx');

            $table->index(["arl_id"], 'visitas_arl_id_foreign_idx');

            $table->index(["tipo_sangre_id"], 'visitas_yipo_sangre_id_foreign_idx');

            $table->index(["eps_id"], 'visitas_eps_id_foreign_idx');



            $table->foreign('usuario_id', 'visitas_usuario_id_foreign_idx')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('arl_id', 'visitas_arl_id_foreign_idx')
                ->references('id')->on('arl')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('tipo_sangre_id', 'visitas_yipo_sangre_id_foreign_idx')
                ->references('id')->on('tipo_sangre')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('eps_id', 'visitas_eps_id_foreign_idx')
                ->references('id')->on('eps')
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
