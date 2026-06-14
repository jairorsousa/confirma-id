<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('query_type', 30);
            $table->char('queried_term_hash', 64);
            $table->string('queried_term_masked')->nullable();
            $table->string('result')->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('origin')->nullable();
            $table->string('credential_label')->nullable();
            $table->timestamps();

            $table->index(['partner_id', 'query_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_queries');
    }
};
