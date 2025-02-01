<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PropertySeeder::class,
            PropertyDocumentSeeder::class,
        ]);

        // Initial platform settings
        $defaultSettings = [
            'platform_fee_percentage' => [
                'value' => '2.5',
                'type' => 'float',
                'group' => 'fees',
                'description' => 'Default platform fee percentage',
            ],
            'default_service_fee_percentage' => [
                'value' => '5',
                'type' => 'float',
                'group' => 'fees',
                'description' => 'Default service fee percentage for properties',
            ],
            'late_fee_percentage' => [
                'value' => '1',
                'type' => 'float',
                'group' => 'fees',
                'description' => 'Late fee percentage per day for overdue installments',
            ],
            'required_property_documents' => [
                'value' => json_encode([
                    'certificate',
                    'tax_documentation',
                    'building_permit',
                    'floor_plan',
                ]),
                'type' => 'json',
                'group' => 'documents',
                'description' => 'Required documents for property listing',
            ],
            'required_renter_documents' => [
                'value' => json_encode([
                    'id_card',
                    'income_statement',
                    'employment_letter',
                    'bank_statement',
                ]),
                'type' => 'json',
                'group' => 'documents',
                'description' => 'Required documents for renters',
            ],
            'midtrans_server_key' => [
                'value' => env('MIDTRANS_SERVER_KEY', ''),
                'type' => 'string',
                'group' => 'integrations',
                'description' => 'Midtrans server key for payment integration',
            ],
            'midtrans_client_key' => [
                'value' => env('MIDTRANS_CLIENT_KEY', ''),
                'type' => 'string',
                'group' => 'integrations',
                'description' => 'Midtrans client key for payment integration',
                'is_public' => true,
            ],
            'enable_notifications' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'Enable or disable system notifications',
            ],
            'max_installment_months' => [
                'value' => '24',
                'type' => 'integer',
                'group' => 'general',
                'description' => 'Maximum number of months allowed for installment payments',
            ],
        ];

        foreach ($defaultSettings as $key => $setting) {
            Setting::create(array_merge(['key' => $key], $setting));
        }
    }
}
