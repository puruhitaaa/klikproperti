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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('id_card_number')->nullable();
            $table->json('kyc_data')->nullable();
            $table->timestamp('documents_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'address',
                'is_verified',
                'id_card_number',
                'kyc_data',
                'documents_verified_at'
            ]);
        });
    }
};
