<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quality_prediction_details', function (Blueprint $table) {
            $table->id('id_quality_prediction_detail');
            $table->unsignedBigInteger('id_quality_prediction');
            $table->string('tagName', 45);
            $table->double('probability');
            $table->foreign('id_quality_prediction')->references('id_quality_prediction')->on('quality_predictions')->onDelete('no action')->onUpdate('no action');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_prediction_details');
    }
};
