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
        Schema::create('finance_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();

            // Account Type Verknüpfung
            $table->foreignId('account_type_id')
                ->nullable()
                ->constrained('finance_account_types')
                ->nullOnDelete();

            $table->string('number')->unique();
            $table->string('number_from');          // 10000
            $table->string('number_to')->nullable(); // 10000 oder 10999
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('function')->nullable();
            $table->string('purpose')->nullable();
            $table->string('external_ref')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexe für Performance
            $table->index(['team_id', 'is_active']);
            $table->index(['account_type_id', 'is_active']);
            $table->index('number');
            $table->index(['valid_from', 'valid_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_accounts');
    }
};
