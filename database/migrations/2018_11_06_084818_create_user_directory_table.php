<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDirectoryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_directory', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('owning_branch_id')->unsigned();
            $table->integer('owning_company_id')->unsigned();
            $table->string('type')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('user_directory');
    }
}
