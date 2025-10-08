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
        Schema::create('prediction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prediction_id');
            $table->string('tagName', 45);
            $table->double('probability');
            $table->foreign('prediction_id')->references('id')->on('predictions')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prediction_details');
    }
};
