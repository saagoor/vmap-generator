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
        Schema::create('vmap_ad_breaks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('vmap_id');
            $table->text('vast_url');
            $table->enum('category', ['preroll', 'midroll', 'postroll']);
            $table->string('time_offset')->nullable();
            $table->string('break_type')->default('linear');
            $table->string('repeat_after')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vmap_ad_breaks');
    }
};
