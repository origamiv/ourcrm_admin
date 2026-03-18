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
        Schema::create('excel_exports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('entity');
            $table->string('status')->default('pending'); // pending, processing, done, failed
            $table->string('file_path')->nullable();
            $table->text('error_message')->nullable();
            $table->json('params')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_exports');
    }
};
