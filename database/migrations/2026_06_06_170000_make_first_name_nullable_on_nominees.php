<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nominees', function (Blueprint $table) {
            // Les entités (associations/clubs) n'ont pas de prénom.
            $table->string('first_name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('nominees', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
        });
    }
};
