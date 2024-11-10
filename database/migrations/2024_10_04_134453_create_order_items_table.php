<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id");
            $table->foreign("order_id")->references("id")->on("orders")
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedBigInteger("product_id");
            $table->foreign("product_id")->references("id")->on("products")
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->decimal("price");
            $table->integer("quantity");
            $table->longText("options")->nullable();
            $table->boolean("rstatus")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('order_items');
    }
};
