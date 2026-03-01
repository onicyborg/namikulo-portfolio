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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->foreignUuid('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('client_name', 150);
            $table->string('client_position', 150)->nullable();
            $table->string('company_name', 150)->nullable();
            $table->text('content');
            $table->unsignedTinyInteger('rating')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);

            $table->timestamps();

            $table->index('category_id');
            $table->index('created_by');
            $table->index('is_published');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
