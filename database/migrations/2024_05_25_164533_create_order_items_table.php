<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items', 'item_id')->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('price', 8, 2); // Prix unitaire au moment de la commande
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
