<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('status'); // Scheduled, Pending Response, Completed
            $table->dateTime('scheduled_at')->nullable();
            $table->string('with_name'); // e.g. Ahmad Al-Rashid
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('consultations'); }
};
