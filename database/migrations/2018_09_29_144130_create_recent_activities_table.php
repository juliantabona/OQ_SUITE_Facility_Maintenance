<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecentActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('recent_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->json('detail')->nullable();
            $table->integer('who_created_id')->unsigned()->nullable();
            $table->integer('company_branch_id')->unsigned()->nullable();
            $table->morphs('trackable');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('recent_activities');
    }
}
