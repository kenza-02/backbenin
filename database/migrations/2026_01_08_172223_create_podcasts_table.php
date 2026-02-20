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
        Schema::create('sn_podcasts', function (Blueprint $table) {
    $table->id();
    $table->string('libelle');
    $table->text('description')->nullable();
    $table->foreignId('membre_id')->constrained('sn_membres')->onDelete('cascade');
    $table->foreignId('categorie_id')->constrained('sn_categories')->onDelete('cascade');
    $table->string('fichier');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sn_podcasts');
    }
};
