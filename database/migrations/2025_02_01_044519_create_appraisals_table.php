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
        Schema::create('appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('users');
            $table->decimal('appraised_value', 15, 2);
            $table->text('comments')->nullable();
            $table->json('assessment_details')->nullable();
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending');
            $table->boolean('is_recommended')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('scheduled_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appraisals');
    }
};
