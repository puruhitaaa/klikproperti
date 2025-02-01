<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('owner')->get();

        if ($users->isEmpty()) {
            $users = [User::first()];
        }

        $properties = [
            [
                'title' => 'Apartemen Mewah Green Bay Pluit',
                'description' => 'Apartemen mewah dengan pemandangan laut yang indah, dilengkapi dengan fasilitas lengkap seperti kolam renang, gym, dan taman bermain.',
                'price' => 850000000,
                'type' => 'sale',
                'status' => 'available',
                'location_address' => 'Jl. Pluit Karang Ayu B1',
                'city' => 'Jakarta Utara',
                'state' => 'DKI Jakarta',
                'postal_code' => '14450',
                'latitude' => -6.1190,
                'longitude' => 106.7892,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'area' => 45.5,
                'features' => json_encode(['AC', 'Water Heater', 'Furnished', 'Swimming Pool', 'Gym']),
                'service_fee_percentage' => 2.5,
                'is_recommended' => true,
            ],
            [
                'title' => 'Rumah Cluster BSD City',
                'description' => 'Rumah cluster modern di kawasan elite BSD City. Lingkungan aman dan nyaman, dekat dengan sekolah dan pusat perbelanjaan.',
                'price' => 2500000000,
                'type' => 'sale',
                'status' => 'available',
                'location_address' => 'Cluster Green Valley BSD',
                'city' => 'Tangerang Selatan',
                'state' => 'Banten',
                'postal_code' => '15310',
                'latitude' => -6.2988,
                'longitude' => 106.6857,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area' => 120.0,
                'features' => json_encode(['Carport', 'Garden', 'Security 24/7', 'Smart Home']),
                'service_fee_percentage' => 2.0,
                'is_recommended' => true,
            ],
            [
                'title' => 'Kost Exclusive Tebet',
                'description' => 'Kost exclusive dengan fasilitas lengkap, lokasi strategis dekat stasiun Tebet dan pusat kuliner.',
                'price' => 3500000,
                'type' => 'rent',
                'status' => 'available',
                'location_address' => 'Jl. Tebet Barat Dalam',
                'city' => 'Jakarta Selatan',
                'state' => 'DKI Jakarta',
                'postal_code' => '12810',
                'latitude' => -6.2270,
                'longitude' => 106.8456,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'area' => 20.0,
                'features' => json_encode(['AC', 'Wifi', 'Laundry', 'Cleaning Service']),
                'service_fee_percentage' => 5.0,
                'is_recommended' => false,
            ],
            [
                'title' => 'Ruko 3 Lantai Kelapa Gading',
                'description' => 'Ruko strategis di kawasan bisnis Kelapa Gading, cocok untuk usaha atau kantor.',
                'price' => 12000000,
                'type' => 'rent',
                'status' => 'available',
                'location_address' => 'Jl. Boulevard Raya Kelapa Gading',
                'city' => 'Jakarta Utara',
                'state' => 'DKI Jakarta',
                'postal_code' => '14240',
                'latitude' => -6.1590,
                'longitude' => 106.9055,
                'bedrooms' => 0,
                'bathrooms' => 2,
                'area' => 150.0,
                'features' => json_encode(['3 Floors', 'Wide Parking Area', 'Corner Position']),
                'service_fee_percentage' => 3.0,
                'is_recommended' => true,
            ],
        ];

        foreach ($properties as $property) {
            $user = $users[array_rand($users instanceof \Illuminate\Database\Eloquent\Collection ? $users->toArray() : $users)];
            Property::create(array_merge($property, ['user_id' => $user->id]));
        }
    }
}
