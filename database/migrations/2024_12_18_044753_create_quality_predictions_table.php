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
        Schema::create('quality_predictions', function (Blueprint $table) {
            $table->id('id_quality_prediction');
            $table->unsignedBigInteger('idphotos');
            $table->timestamp('created');
            $table->text('project');
            $table->foreign('idphotos')->references('idphotos')->on('photos')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_predictions');
    }
};
