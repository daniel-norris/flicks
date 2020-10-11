<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('backdrop_path', 200)->nullable();
            $table->string('poster_path', 200)->nullable();
            $table->integer('budget')->nullable();
            $table->text('overview')->nullable();
            $table->float('popularity', 3, 1)->nullable();
            $table->date('release_date')->nullable();
            $table->integer('revenue')->nullable();
            $table->integer('runtime')->nullable();
            $table->string('status', 100)->nullable();
            $table->float('vote_average', 3, 1)->nullable();
            $table->integer('vote_count')->nullable();
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
        Schema::dropIfExists('movies');
    }
}
