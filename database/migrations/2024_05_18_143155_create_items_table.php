<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id');
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->foreignId('category_id')->constrained('categories', 'category_id')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
