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
        Schema::create('rider_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('license_number')->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('guarantor_name')->nullable();
            $table->string('guarantor_phone')->nullable();
            $table->text('guarantor_address')->nullable();
            $table->string('guarantor_id_type')->nullable();
            $table->string('guarantor_id_number')->nullable();
            $table->string('guarantor_photo_path')->nullable();
            $table->string('id_type')->nullable();
            $table->string('id_number')->nullable();
            $table->string('id_document_path')->nullable();
            $table->string('drivers_license_path')->nullable();
            $table->string('passport_photo_path')->nullable();
            $table->string('facial_verification_path')->nullable();
            $table->timestamp('terms_accepted_at')->nullable();
            $table->timestamp('onboarding_completed_at')->nullable();
            $table->foreignId('assigned_vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->foreignId('assigned_route_id')->nullable()->constrained('routes')->nullOnDelete();
            $table->string('assistant_name')->nullable();
            $table->string('assistant_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rider_profiles');
    }
};
