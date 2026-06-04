<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // person  = nominés = personnes (Prénom + Nom)
            // entity  = nominés = associations / clubs / événements (un seul nom)
            $table->enum('nominee_type', ['person', 'entity'])->default('person')->after('voter_type');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('nominee_type');
        });
    }
};
