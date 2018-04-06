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

        Schema::create($tableName['point_event_type'] ,function(Blueprint $table){
            $table->increments('id');
            $table->text('name');
            $table->boolean('is_increase')->nullable();
            $table->boolean('is_deduction')->nullable();
            $table->timestamps();
        });

        Schema::create($tableName['point_events'] ,function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedInteger('event_type_id')->index();
            $table->string('body' ,50);
            $table->unsignedInteger('user_id')->index();
            $table->morphs('pointable');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create($tableName['point_activities'] ,function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedInteger('point_event_id')->index();
            $table->unsignedInteger('point_id');
            $table->integer('number');
            $table->integer('before_point');
            $table->integer('after_point');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_events');

        Schema::dropIfExists('point_event_able');
    }
}
