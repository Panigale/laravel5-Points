<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('points.table_names');


        Schema::create($tableName['point_events'] ,function(Blueprint $table){
            $table->increments('id');
            $table->text('name');
            $table->timestamps();
        });

        Schema::create($tableName['point_event_ables'] ,function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('point_increase_id');
            $table->unsignedInteger('point_event_type_id');
            $table->text('body');
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
        $tableName = config('points.table_names');

        Schema::dropIfExists($tableName['point_events']);

        Schema::dropIfExists($tableName['point_event_ables']);
    }
}
