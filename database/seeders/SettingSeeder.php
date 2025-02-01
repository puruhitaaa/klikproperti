<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'KlikProperti',
                'type' => 'string',
                'description' => 'Nama website',
                'group' => 'general',
                'is_public' => true,
            ],
            [
                'key' => 'site_description',
                'value' => 'Platform jual beli dan sewa properti terpercaya',
                'type' => 'string',
                'description' => 'Deskripsi website',
                'group' => 'general',
                'is_public' => true,
            ],

            // Contact Settings
            [
                'key' => 'contact_email',
                'value' => 'info@klikproperti.com',
                'type' => 'string',
                'description' => 'Email kontak utama',
                'group' => 'contact',
                'is_public' => true,
            ],
            [
                'key' => 'contact_phone',
                'value' => '+62 812-3456-7890',
                'type' => 'string',
                'description' => 'Nomor telepon kontak utama',
                'group' => 'contact',
                'is_public' => true,
            ],

            // Fee Settings
            [
                'key' => 'default_service_fee',
                'value' => '2.5',
                'type' => 'float',
                'description' => 'Persentase biaya layanan default',
                'group' => 'fee',
                'is_public' => true,
            ],
            [
                'key' => 'minimum_service_fee',
                'value' => '1.0',
                'type' => 'float',
                'description' => 'Persentase minimum biaya layanan',
                'group' => 'fee',
                'is_public' => false,
            ],

            // Social Media
            [
                'key' => 'social_media_links',
                'value' => json_encode([
                    'facebook' => 'https://facebook.com/klikproperti',
                    'instagram' => 'https://instagram.com/klikproperti',
                    'twitter' => 'https://twitter.com/klikproperti'
                ]),
                'type' => 'json',
                'description' => 'Link media sosial',
                'group' => 'social',
                'is_public' => true,
            ],

            // System Settings
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Status mode maintenance website',
                'group' => 'system',
                'is_public' => false,
            ],
            [
                'key' => 'max_property_images',
                'value' => '10',
                'type' => 'integer',
                'description' => 'Jumlah maksimum gambar per properti',
                'group' => 'system',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
