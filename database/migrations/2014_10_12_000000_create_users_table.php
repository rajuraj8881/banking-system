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
            Schema::create('users', function (Blueprint $table) {
                $table->id()->index();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->enum('account_type', ['Individual', 'Business']);
                $table->double('balance')->default(0);
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

        Schema::dropIfExists('users');

        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
};
