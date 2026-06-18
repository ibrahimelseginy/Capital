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
        Schema::create('project_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role'); // e.g. "مدير مشروع", "محاسب", "استشاري"
            $table->timestamps();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['project_manager_id']);
            $table->dropForeign(['account_manager_id']);
            $table->dropForeign(['financial_manager_id']);
            $table->dropForeign(['executive_manager_id']);
            
            $table->dropColumn([
                'project_manager_id', 
                'account_manager_id', 
                'financial_manager_id', 
                'executive_manager_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('account_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('financial_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('executive_manager_id')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::dropIfExists('project_user');
    }
};
