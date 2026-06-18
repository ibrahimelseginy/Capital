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
            $table->string('project_manager_image')->nullable();
            $table->string('account_manager_image')->nullable();
            $table->string('financial_manager_image')->nullable();
            $table->string('executive_manager_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'project_manager_image',
                'account_manager_image',
                'financial_manager_image',
                'executive_manager_image'
            ]);
        });
    }
};
