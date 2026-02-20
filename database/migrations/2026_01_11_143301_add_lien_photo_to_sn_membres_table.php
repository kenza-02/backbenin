<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sn_membres', function (Blueprint $table) {
            $table->string('lien_photo', 245)->nullable()->after('nom');
        });
    }

    public function down(): void
    {
        Schema::table('sn_membres', function (Blueprint $table) {
            $table->dropColumn('lien_photo');
        });
    }
};
