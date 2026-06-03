<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Lien privé de candidature + état ouvert/fermé.
            $table->string('candidacy_token', 64)->nullable()->unique()->after('slug');
            $table->boolean('candidacy_open')->default(false)->after('candidacy_token');
        });

        Schema::table('nominees', function (Blueprint $table) {
            // Candidature validée par l'admin (les candidatures via lien arrivent à false).
            $table->boolean('is_approved')->default(true)->after('is_active');
        });

        // Génère un jeton pour les récompenses existantes.
        foreach (DB::table('categories')->whereNull('candidacy_token')->pluck('id') as $id) {
            DB::table('categories')->where('id', $id)->update(['candidacy_token' => Str::random(40)]);
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['candidacy_token', 'candidacy_open']);
        });

        Schema::table('nominees', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
};
