<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_surat_jalan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_jalan_id')->constrained('surat_jalan')->cascadeOnDelete();
            $table->string('type'); // 'item' or 'group_title'
            $table->text('group_title_text')->nullable();
            $table->foreignId('barang_id')->nullable()->constrained('master_barang')->nullOnDelete();
            $table->integer('qty')->nullable();
            $table->string('remark')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_surat_jalan');
    }
};
