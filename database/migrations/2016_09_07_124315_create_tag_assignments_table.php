<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('image_id')->unsigned();
            $table->integer('tag_id')->unsigned();

           	$table->unique(array('image_id', 'tag_id'));

            $table->foreign('image_id')->references('id')->on('images');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tag_assignments');
    }
}
