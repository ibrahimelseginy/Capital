<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('exit_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->date('entry_date');
            $table->date('exit_date');
            $table->decimal('invested_amount', 15, 2);
            $table->decimal('returned_amount', 15, 2);
            $table->string('multiple');
            $table->string('method'); // Acquisition, IPO
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('exit_records'); }
};
