<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('voter_type', ['eleve', 'professeur', 'both'])->default('eleve');
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('max_nominees')->default(5);
            $table->boolean('requires_proof')->default(false);
            $table->enum('proof_type', ['url', 'file', 'both'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
