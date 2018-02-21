<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('points.table_names');

        Schema::create($tableName['points'], function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('rule_id');
            $table->unsignedInteger('number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create($tableName['point_rules'] ,function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->dateTime('expiry_at')->nullable();
            $table->timestamps();
        });

        Schema::create($tableName['point_usages'] ,function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('point_id');
            $table->integer('number');
            $table->integer('before_number');
            $table->integer('after_number');
            $table->timestamps();
        });

        Schema::create($tableName['point_increases'] ,function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('point_id');
            $table->integer('number');
            $table->integer('before_number');
            $table->integer('after_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
