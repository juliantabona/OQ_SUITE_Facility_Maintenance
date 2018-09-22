<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobcardProcessFormsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('process_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->text('form_structure')->nullable();
            $table->text('doc_structure')->nullable();
            $table->string('type')->nullable();
            $table->boolean('selected')->default(0);
            $table->boolean('deletable')->default(0);
            $table->integer('company_id')->unsigned();
            $table->integer('who_created_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('process_forms');
    }
}
