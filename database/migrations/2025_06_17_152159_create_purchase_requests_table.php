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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_status_id')->constrained('request_statuses')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('purchase_invoices')->onDelete('set null');
            $table->string('requested_by');
            $table->string('approved_by')->nullable();
            $table->string('item_requested');
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->decimal('price_per_pcs', 10, 2);
            $table->date('request_date');
            $table->decimal('approved_budget', 10, 2)->nullable();
            $table->decimal('used_budget', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
