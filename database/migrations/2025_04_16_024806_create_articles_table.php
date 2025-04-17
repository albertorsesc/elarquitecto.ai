<?php

use App\Models\User;
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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignIdFor(User::class, 'author_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->mediumText('body');
            $table->string('original_url')->nullable(); // For cross-posting attribution
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('view_count')->default(0);
            $table->timestamp('published_at')->nullable(); // Null means it's a draft
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
