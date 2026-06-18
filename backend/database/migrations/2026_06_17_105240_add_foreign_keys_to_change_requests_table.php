<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('change_requests')) {
            // Delete orphaned records where user_id does not exist in users table
            DB::table('change_requests')
                ->whereNotNull('user_id')
                ->whereNotIn('user_id', function ($query) {
                    $query->select('id')->from('users');
                })
                ->delete();

            // Delete orphaned records where reviewer_id does not exist in users table
            DB::table('change_requests')
                ->whereNotNull('reviewer_id')
                ->whereNotIn('reviewer_id', function ($query) {
                    $query->select('id')->from('users');
                })
                ->delete();

            Schema::table('change_requests', function (Blueprint $table) {
                $table->foreign('reviewer_id', 'change_requests_reviewer_id_foreign')
                      ->references('id')
                      ->on('users');
                      
                $table->foreign('user_id', 'change_requests_user_id_foreign')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->dropForeign('change_requests_reviewer_id_foreign');
            $table->dropForeign('change_requests_user_id_foreign');
        });
    }
};
