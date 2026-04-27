<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_jalan', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat_jalan')->unique();
            $table->date('tanggal');
            $table->string('tujuan');
            $table->string('attn')->nullable();
            $table->string('phone_header')->nullable();
            $table->text('note')->nullable();
            $table->string('taken_by')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('phone_footer')->nullable();
            $table->date('eta')->nullable();
            $table->string('foreman')->nullable();
            $table->string('woc')->nullable();
            $table->string('status')->default('PENDING'); // PENDING, APPROVED, REJECTED
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_jalan');
    }
};
