<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('exit_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('request_date');
            $table->string('type'); // Partial Exit, Full Exit
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('status')->default('Under Review'); // Under Review, Completed
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('exit_requests'); }
};
