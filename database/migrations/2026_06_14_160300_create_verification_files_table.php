<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verification_id')->constrained()->cascadeOnDelete();
            $table->string('file_type', 20);
            $table->string('disk')->default('s3');
            $table->string('path')->unique();
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size_bytes');
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();

            $table->unique(['verification_id', 'file_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_files');
    }
};
