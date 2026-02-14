<?php

namespace Database\Seeders;

use App\Models\ManagerProfile;
use App\Models\Payment;
use App\Models\PaymentPlan;
use App\Models\RiderProfile;
use App\Models\Route;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@skillride.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '08000000001',
            'status' => 'active',
            'is_verified' => true,
            'verified_at' => now(),
            'email_verified_at' => now(),
        ]);

        // Create Managers
        $manager1 = User::create([
            'name' => 'John Manager',
            'email' => 'manager@skillride.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'phone' => '08000000002',
            'status' => 'active',
            'is_verified' => true,
            'verified_at' => now(),
            'email_verified_at' => now(),
        ]);

        ManagerProfile::create([
            'user_id' => $manager1->id,
            'employee_id' => 'MGR-001',
            'department' => 'Fleet Operations',
            'zone' => 'Zone A',
            'ward' => 'Central Ward',
            'commission_rate' => 5.00,
            'bank_name' => 'First Bank',
            'bank_account_number' => '0123456789',
            'bank_account_name' => 'John Manager',
        ]);

        $manager2 = User::create([
            'name' => 'Sarah Manager',
            'email' => 'manager2@skillride.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'phone' => '08000000003',
            'status' => 'active',
            'is_verified' => true,
            'verified_at' => now(),
            'email_verified_at' => now(),
        ]);

        ManagerProfile::create([
            'user_id' => $manager2->id,
            'employee_id' => 'MGR-002',
            'department' => 'Fleet Operations',
            'zone' => 'Zone B',
            'commission_rate' => 5.00,
        ]);

        // Create Routes
        $route1 = Route::create([
            'name' => 'Wuse - Garki',
            'description' => 'Main route from Wuse Market to Garki',
            'start_location' => 'Wuse Market',
            'end_location' => 'Garki Area 1',
            'start_latitude' => 9.0579,
            'start_longitude' => 7.4891,
            'end_latitude' => 9.0400,
            'end_longitude' => 7.4900,
            'distance_km' => 5.2,
            'estimated_duration_minutes' => 20,
            'ward' => 'Central',
            'lga' => 'AMAC',
            'state' => 'FCT',
            'status' => 'active',
            'assigned_manager_id' => $manager1->id,
        ]);

        $route2 = Route::create([
            'name' => 'Kubwa - Zuba',
            'description' => 'Route from Kubwa to Zuba junction',
            'start_location' => 'Kubwa',
            'end_location' => 'Zuba',
            'start_latitude' => 9.1176,
            'start_longitude' => 7.3248,
            'end_latitude' => 9.0770,
            'end_longitude' => 7.2500,
            'distance_km' => 12.0,
            'estimated_duration_minutes' => 35,
            'ward' => 'Kubwa',
            'lga' => 'Bwari',
            'state' => 'FCT',
            'status' => 'active',
            'assigned_manager_id' => $manager2->id,
        ]);

        // Create Vehicles
        $vehicles = [];
        $kekes = [
            ['reg' => 'ABJ-001-KK', 'make' => 'Bajaj', 'model' => 'RE', 'color' => 'Yellow'],
            ['reg' => 'ABJ-002-KK', 'make' => 'TVS', 'model' => 'King', 'color' => 'Yellow'],
            ['reg' => 'ABJ-003-KK', 'make' => 'Bajaj', 'model' => 'RE Compact', 'color' => 'Green'],
            ['reg' => 'ABJ-004-KK', 'make' => 'Piaggio', 'model' => 'Ape', 'color' => 'Blue'],
            ['reg' => 'ABJ-005-KK', 'make' => 'Bajaj', 'model' => 'RE', 'color' => 'Yellow'],
        ];

        foreach ($kekes as $i => $keke) {
            $vehicles[] = Vehicle::create([
                'registration_number' => $keke['reg'],
                'type' => 'keke_napep',
                'make' => $keke['make'],
                'model' => $keke['model'],
                'year' => 2023,
                'color' => $keke['color'],
                'status' => 'active',
                'assigned_manager_id' => $i < 3 ? $manager1->id : $manager2->id,
                'insurance_expiry' => now()->addMonths(6),
                'road_worthiness_expiry' => now()->addMonths(8),
                'purchase_price' => 850000 + ($i * 50000),
                'purchased_at' => now()->subMonths(6),
                'current_latitude' => 9.0579 + ($i * 0.005),
                'current_longitude' => 7.4891 + ($i * 0.003),
                'last_location_update' => now()->subMinutes(rand(1, 60)),
            ]);
        }

        // Create Riders
        $riderNames = ['Musa Ibrahim', 'Ahmed Yusuf', 'Chukwu Emeka', 'Daniel Okafor', 'Bala Abdullahi'];
        foreach ($riderNames as $i => $name) {
            $rider = User::create([
                'name' => $name,
                'email' => 'rider' . ($i + 1) . '@skillride.com',
                'password' => Hash::make('password'),
                'role' => 'rider',
                'phone' => '0800000' . str_pad($i + 10, 4, '0', STR_PAD_LEFT),
                'status' => 'active',
                'is_verified' => true,
                'verified_at' => now(),
                'email_verified_at' => now(),
                'address' => 'No. ' . ($i + 1) . ' Sample Street',
                'city' => 'Abuja',
                'state' => 'FCT',
                'gender' => 'male',
                'date_of_birth' => now()->subYears(25 + $i),
            ]);

            RiderProfile::create([
                'user_id' => $rider->id,
                'license_number' => 'DL-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'license_expiry' => now()->addYear(),
                'guarantor_name' => 'Guarantor ' . ($i + 1),
                'guarantor_phone' => '0900000' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'guarantor_address' => 'Guarantor Address ' . ($i + 1),
                'assigned_vehicle_id' => $vehicles[$i]->id,
                'assigned_route_id' => $i < 3 ? $route1->id : $route2->id,
                'terms_accepted_at' => now(),
                'onboarding_completed_at' => now(),
            ]);

            $vehicles[$i]->update(['assigned_rider_id' => $rider->id]);

            for ($j = 0; $j < 10; $j++) {
                $dueDate = now()->subDays(30 - ($j * 3));
                $isPaid = $j < 8;
                Payment::create([
                    'rider_id' => $rider->id,
                    'amount' => 2000,
                    'payment_method' => ['cash', 'bank_transfer', 'paystack', 'pos'][rand(0, 3)],
                    'reference' => 'PAY-' . strtoupper(Str::random(10)),
                    'status' => $isPaid ? 'completed' : 'pending',
                    'payment_date' => $isPaid ? $dueDate : null,
                    'due_date' => $dueDate,
                    'paid_at' => $isPaid ? $dueDate : null,
                ]);
            }
        }

        // Payment Plans
        PaymentPlan::create([
            'name' => 'Keke Daily Plan',
            'description' => 'Daily payment for keke napep riders',
            'amount' => 2000,
            'frequency' => 'daily',
            'vehicle_type' => 'keke_napep',
            'duration_days' => 365,
            'total_amount' => 730000,
            'is_active' => true,
        ]);

        PaymentPlan::create([
            'name' => 'Keke Weekly Plan',
            'description' => 'Weekly payment for keke napep riders',
            'amount' => 12000,
            'frequency' => 'weekly',
            'vehicle_type' => 'keke_napep',
            'duration_days' => 365,
            'total_amount' => 624000,
            'is_active' => true,
        ]);

        PaymentPlan::create([
            'name' => 'Car Monthly Plan',
            'description' => 'Monthly payment for car riders',
            'amount' => 80000,
            'frequency' => 'monthly',
            'vehicle_type' => 'car',
            'duration_days' => 365,
            'total_amount' => 960000,
            'is_active' => true,
        ]);

        // Site Settings
        $settings = [
            ['key' => 'app_name', 'value' => 'SkillRide', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_name', 'value' => 'SkillRide Fleet Management', 'group' => 'general', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'info@skillride.com', 'group' => 'general', 'type' => 'text'],
            ['key' => 'support_phone', 'value' => '+234 800 000 0000', 'group' => 'general', 'type' => 'text'],
            ['key' => 'primary_color', 'value' => '#10B981', 'group' => 'branding', 'type' => 'text'],
            ['key' => 'secondary_color', 'value' => '#3B82F6', 'group' => 'branding', 'type' => 'text'],
            ['key' => 'sms_enabled', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'email_enabled', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'push_enabled', 'value' => '0', 'group' => 'notifications', 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }
    }
}
