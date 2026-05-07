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
        Schema::table('detail_surat_jalan', function (Blueprint $table) {
            $table->string('manual_asset_id')->nullable()->after('group_title_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_surat_jalan', function (Blueprint $table) {
            $table->dropColumn('manual_asset_id');
        });
    }
};
