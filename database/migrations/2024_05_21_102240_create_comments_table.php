<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->text('content');
            $table->foreignId('post_id')->constrained('posts', 'post_id')->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->boolean('visible')->default(false); // Un commentaire doit être approuvé avant d'être visible
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
