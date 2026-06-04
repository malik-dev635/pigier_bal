<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::table('nominees', function (Blueprint $table) {
            // false = nominé "hors vote" (présent dans le programme, pas au vote).
            $table->boolean('is_votable')->default(true)->after('is_approved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');

        Schema::table('nominees', function (Blueprint $table) {
            $table->dropColumn('is_votable');
        });
    }
};
