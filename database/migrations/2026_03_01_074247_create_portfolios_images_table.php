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
        Schema::create('portfolios_images', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('portfolio_id')
                ->constrained('portfolios')
                ->cascadeOnDelete();

            $table->string('image_path', 255);
            $table->string('alt_text', 255)->nullable();
            $table->boolean('is_thumbnail')->default(false);

            $table->timestamps();

            $table->index('portfolio_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios_images');
    }
};
