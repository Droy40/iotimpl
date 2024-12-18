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
        Schema::create('type_prediction_details', function (Blueprint $table) {
            $table->id('id_type_prediction_detail');
            $table->unsignedBigInteger('id_type_prediction');
            $table->string('tagName', 45);
            $table->double('probability');
            $table->foreign('id_type_prediction')->references('id_type_prediction')->on('type_predictions')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_prediction_details');
    }
};
