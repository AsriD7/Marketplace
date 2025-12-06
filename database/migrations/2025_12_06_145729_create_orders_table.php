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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // pelanggan
            $table->foreignId('store_id')->constrained()->onDelete('cascade'); // toko tujuan
            $table->text('alamat_kirim')->nullable();
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('ongkir')->default(0);
            $table->unsignedBigInteger('total')->default(0);
            $table->string('status')->default('created'); // enum as text: created, paid_pending_validation, payment_validated, preparing, shipped, delivered, completed, cancelled
            $table->string('payment_status')->default('pending'); // pending/confirmed/rejected
            $table->foreignId('admin_validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('admin_validated_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
