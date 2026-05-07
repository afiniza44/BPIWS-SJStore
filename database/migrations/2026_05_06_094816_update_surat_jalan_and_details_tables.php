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
            $table->string('manual_nama_barang')->nullable()->after('type');
            $table->string('manual_satuan')->nullable()->after('manual_nama_barang');
        });

        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->string('status')->default('APPROVED');
        });

        Schema::table('detail_surat_jalan', function (Blueprint $table) {
            $table->dropColumn(['manual_nama_barang', 'manual_satuan']);
        });
    }
};
