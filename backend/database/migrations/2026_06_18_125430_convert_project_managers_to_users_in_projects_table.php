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
            // Drop old string and image columns
            $table->dropColumn([
                'project_manager', 'account_manager', 'financial_manager', 'executive_manager',
                'project_manager_image', 'account_manager_image', 'financial_manager_image', 'executive_manager_image'
            ]);

            // Add foreign key columns
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('account_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('financial_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('executive_manager_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['project_manager_id']);
            $table->dropForeign(['account_manager_id']);
            $table->dropForeign(['financial_manager_id']);
            $table->dropForeign(['executive_manager_id']);
            
            $table->dropColumn([
                'project_manager_id', 'account_manager_id', 'financial_manager_id', 'executive_manager_id'
            ]);

            // Restore old columns
            $table->string('project_manager')->nullable();
            $table->string('account_manager')->nullable();
            $table->string('financial_manager')->nullable();
            $table->string('executive_manager')->nullable();
            $table->string('project_manager_image')->nullable();
            $table->string('account_manager_image')->nullable();
            $table->string('financial_manager_image')->nullable();
            $table->string('executive_manager_image')->nullable();
        });
    }
};
