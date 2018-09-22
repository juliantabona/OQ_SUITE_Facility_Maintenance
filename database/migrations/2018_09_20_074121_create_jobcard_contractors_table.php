<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobcardContractorsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jobcard_contractors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jobcard_id')->unsigned();
            $table->integer('contractor_id')->unsigned();
            $table->decimal('amount', 8, 2)->nullable();
            $table->string('quotation_doc_url')->nullable();
            $table->integer('who_created_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('jobcard_contractors');
    }
}
