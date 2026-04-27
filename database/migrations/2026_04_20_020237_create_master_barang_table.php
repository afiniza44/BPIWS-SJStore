<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_barang', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('nama_barang');
            $table->string('satuan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_barang');
    }
};
