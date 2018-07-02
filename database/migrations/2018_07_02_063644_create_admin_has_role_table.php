<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminHasRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_has_role', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('adminer_id');
            $table->integer('role_id');
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
            $table->foreign('role_id')->references('id')->on('role');
            $table->foreign('adminer_id')->references('id')->on('adminer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_has_role');
    }
}
