<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leadboxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('team_id')->nullable();
            $table->integer('blog_id');
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->longText('main_text')->nullable();
            $table->string('button_text')->nullable();
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
        Schema::dropIfExists('leadboxes');
    }
}
