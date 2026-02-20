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
        Schema::table('sn_evenements', function (Blueprint $table) {
            $table->string('heure_debut')->after('date_fin');
            $table->string('heure_fin')->after('heure_debut');
            $table->string('image')->nullable()->after('heure_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sn_evenements', function (Blueprint $table) {
            $table->dropColumn(['heure_debut', 'heure_fin', 'image']);
        });
    }
};
