<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id()->index();
                $table->foreignId('user_id')->constrained(
                    table: 'users',
                    indexName: 'user_id'
                )->onDelete('cascade');
                $table->enum('transaction_type', ['deposit', 'withdrawal']);
                $table->double('amount');
                $table->decimal('fee', 15, 2)->default(0);
                $table->timestamps();
            });
        }

        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Drop the table if it exists
        Schema::dropIfExists('transactions');

        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
};
