<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->unique();
            $table->string('name');
            $table->string('category')->default('Commodity');
            $table->decimal('contract_size', 12, 2)->default(100.00);
            $table->unsignedInteger('default_leverage')->default(100);
            $table->decimal('margin_call_level', 5, 2)->default(70.00);
            $table->decimal('stop_out_level', 5, 2)->default(30.00);
            $table->unsignedTinyInteger('price_decimals')->default(2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instruments');
    }
};