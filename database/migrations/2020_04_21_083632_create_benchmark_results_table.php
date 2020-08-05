<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBenchmarkResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benchmark_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('benchmark_id');
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('benchmark_id')->references('id')->on('benchmarks')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->float('equal_score')->default(0.0);
            $table->float('penalized_score')->default(0.0);
            $table->float('word_accuracy')->default(0.0);
            $table->float('sequence_accuracy')->default(0.0);
            $table->integer('num_sentences')->default(0);
            $table->timestamps();
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
        Schema::dropIfExists('benchmark_results');
    }
}
