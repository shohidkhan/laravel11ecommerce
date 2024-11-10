<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->decimal("subtotal");
            $table->decimal("discount")->default(0);
            $table->decimal("vat");
            $table->decimal("total");
            $table->string("name");
            $table->string("phone");
            $table->string("locality");
            $table->string("address");
            $table->string("city");
            $table->string("state");
            $table->string("country");
            $table->string("landmark")->nullable();
            $table->string("zipcode");
            $table->string("type")->default('home');
            $table->enum("status", ["ordered", "delivered", "canceled"])->default("ordered");
            $table->boolean("is_shipping_different")->default(false);
            $table->date("delivered_date")->nullable();
            $table->date("cancled_date")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
