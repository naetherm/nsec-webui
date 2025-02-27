<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNsecBenchmarkAlignmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nsec_benchmark_alignment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('benchmark_id');
            $table->foreign('benchmark_id')->references('id')->on('nsec_benchmark')->onDelete('cascade');
            $table->json('alignment');
            $table->string('groundtruth');
            $table->string('source');
            $table->json('groundtruth_tokens');
            $table->json('source_tokens');
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
        Schema::dropIfExists('nsec_benchmark_alignment');
    }
}
