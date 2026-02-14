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
            $table->string('role')->default('rider'); // admin, manager, rider
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->string('status')->default('pending'); // active, inactive, suspended, pending
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('next_of_kin_name')->nullable();
            $table->string('next_of_kin_phone')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->boolean('facial_verified')->default(false);
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('locale')->default('en');
            $table->boolean('dark_mode')->default(false);

            $table->index('role');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['status']);

            $table->dropColumn([
                'role',
                'phone',
                'avatar',
                'status',
                'address',
                'city',
                'state',
                'date_of_birth',
                'gender',
                'next_of_kin_name',
                'next_of_kin_phone',
                'last_login_at',
                'last_login_ip',
                'is_verified',
                'verified_at',
                'facial_verified',
                'two_factor_enabled',
                'locale',
                'dark_mode',
            ]);
        });
    }
};
