<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->index();
            $table->string('title');
            $table->string('slug')->unique()->index();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->string('business_model')->default('free');
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('website_url')->nullable();
            $table->string('pricing_url')->nullable();
            $table->string('documentation_url')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->json('structured_data')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            // Performance indexes
            $table->index('published_at');
            $table->index('business_model');
            $table->index('title');
            $table->index('is_featured');
            $table->index(['published_at', 'is_featured']);
            $table->index(['slug', 'published_at']);
        });

        Schema::create('tool_categories', function (Blueprint $table) {
            $table->foreignId('tool_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->primary(['tool_id', 'category_id']);
            $table->index('category_id');
        });

        Schema::create('tool_tags', function (Blueprint $table) {
            $table->foreignId('tool_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->primary(['tool_id', 'tag_id']);
            $table->index('tag_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_tags');
        Schema::dropIfExists('tool_categories');
        Schema::dropIfExists('tools');
    }
};
