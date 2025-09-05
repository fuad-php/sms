<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carousel_slide_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('carousel_slide_id');
            $table->string('locale', 10)->index();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('button_text')->nullable();
            $table->timestamps();

            $table->unique(['carousel_slide_id', 'locale']);
            $table->foreign('carousel_slide_id')->references('id')->on('carousel_slides')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carousel_slide_translations');
    }
};


