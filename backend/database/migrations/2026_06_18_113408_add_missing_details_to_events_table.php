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
        Schema::table('events', function (Blueprint $table) {
            $table->string('speaker_name')->nullable();
            $table->string('duration')->nullable();
            $table->string('speaker_profile')->nullable();
            $table->string('invitation_card')->nullable();
            $table->string('qr_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['speaker_name', 'duration', 'speaker_profile', 'invitation_card', 'qr_code']);
        });
    }
};
