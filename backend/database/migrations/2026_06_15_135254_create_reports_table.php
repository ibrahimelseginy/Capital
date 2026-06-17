<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('period');
            $table->string('type'); // Quarterly, Monthly, Due Diligence
            $table->string('status'); // Published, NDA Required
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('reports'); }
};
