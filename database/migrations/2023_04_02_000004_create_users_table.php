<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'users';

    /**
     * Run the migrations.
     * @table usuarios
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('email', 100)->unique();
            $table->tinyInteger('anulado')->default(0);
            $table->string('password', 100);
            $table->unsignedInteger('tipo_usuario_id');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index(["tipo_usuario_id"], 'usuarios_tipo_usuario_id_foreign_idx');


            $table->foreign('tipo_usuario_id', 'usuarios_tipo_usuario_id_foreign_idx')
                ->references('id')->on('tipos_usuarios')
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
