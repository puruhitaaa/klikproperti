<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $propertyTypes = [
            [
                'name' => 'Apartment',
                'description' => 'A residential unit in a multi-unit building or complex',
            ],
            [
                'name' => 'House',
                'description' => 'A standalone residential building, typically for single-family occupancy',
            ],
            [
                'name' => 'Shop House',
                'description' => 'A building that combines commercial space on the ground floor with residential space above',
            ],
            [
                'name' => 'Boarding House',
                'description' => 'A residential property with multiple rooms for rent, typically with shared facilities',
            ],
            [
                'name' => 'Land',
                'description' => 'Undeveloped property without buildings',
            ],
            [
                'name' => 'Office Space',
                'description' => 'Commercial property designed for business operations',
            ],
            [
                'name' => 'Villa',
                'description' => 'A luxury residence, often in a vacation or resort area',
            ],
            [
                'name' => 'Warehouse',
                'description' => 'Industrial property used for storage and distribution',
            ],
            [
                'name' => 'Condominium',
                'description' => 'An individually owned unit in a multi-unit property with shared amenities',
            ],
            [
                'name' => 'Commercial Space',
                'description' => 'Property intended for business use, such as retail or restaurants',
            ],
        ];

        foreach ($propertyTypes as $propertyType) {
            PropertyType::create($propertyType);
        }
    }
}
