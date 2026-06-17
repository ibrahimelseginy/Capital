<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('event_date');
            $table->string('location');
            $table->string('status'); // Registered, Coming Soon
            $table->string('access_type'); // Exclusive, VIP Access, Open
            $table->string('category')->nullable();
            $table->string('time')->nullable();
            $table->text('description')->nullable();
            $table->string('organizer')->default('SEVEN TECH CAPITAL');
            $table->integer('attendees_count')->default(0);
            $table->json('speakers')->nullable();
            $table->json('program')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('events'); }
};
