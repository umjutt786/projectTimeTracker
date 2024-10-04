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
        Schema::create('screenshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_log_id');
            $table->string('screenshot_path'); // Path to the stored screenshot file
            $table->timestamp('captured_at');  // When the screenshot was captured
            $table->timestamps();

            // Foreign key to work_logs table
            $table->foreign('work_log_id')->references('id')->on('work_logs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screenshots');
    }
};
