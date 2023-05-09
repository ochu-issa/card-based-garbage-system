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
        Schema::create('scanned_cards', function (Blueprint $table) {
            $table->id();
            $table->string('card_number');
            $table->string('resident_full_name');
            $table->string('address');
            $table->string('phone_number');
            $table->bigInteger('scanned_times');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scanned_cards');
    }
};
