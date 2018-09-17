<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyBranchesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('company_branches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('destination')->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->string('who_created_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('company_branches');
    }
}
