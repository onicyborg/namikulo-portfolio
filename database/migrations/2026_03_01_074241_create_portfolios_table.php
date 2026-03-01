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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            $table->foreignUuid('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->string('summary', 255)->nullable();
            $table->text('description');
            $table->string('tech_stack', 255)->nullable();
            $table->string('project_url', 255)->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();

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
        Schema::dropIfExists('portfolios');
    }
};
