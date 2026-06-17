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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('sub_category')->nullable();
            $table->decimal('capital', 15, 2)->nullable();
            $table->integer('investors_count')->nullable();
            $table->integer('shareholders_count')->nullable();
            $table->decimal('funding_ask', 15, 2)->nullable();
            $table->integer('total_shares')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'sub_category',
                'capital',
                'investors_count',
                'shareholders_count',
                'funding_ask',
                'total_shares'
            ]);
        });
    }
};
