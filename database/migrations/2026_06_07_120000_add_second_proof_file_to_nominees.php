<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nominees', function (Blueprint $table) {
            $table->string('proof_file_2')->nullable()->after('proof_file');
        });
    }

    public function down(): void
    {
        Schema::table('nominees', function (Blueprint $table) {
            $table->dropColumn('proof_file_2');
        });
    }
};
