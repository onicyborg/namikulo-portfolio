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
        Schema::create('testimonials_images', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('testimonial_id')
                ->constrained('testimonials')
                ->cascadeOnDelete();

            $table->string('image_path', 255);

            $table->timestamps();

            $table->index('testimonial_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials_images');
    }
};
