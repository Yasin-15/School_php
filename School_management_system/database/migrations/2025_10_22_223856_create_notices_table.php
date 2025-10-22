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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('target_audience', ['all', 'students', 'teachers', 'parents', 'admin']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
            $table->datetime('publish_date');
            $table->datetime('expiry_date')->nullable();
            $table->string('attachment')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
