<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertyReviewSeeder extends Seeder
{
    public function run(): void
    {
        $properties = Property::all();
        $users = User::role('renter')->get();

        if ($users->isEmpty()) {
            $users = [User::first()];
        }

        $reviews = [
            [
                'rating' => 5.0,
                'comment' => 'Properti yang sangat bagus dan terawat. Lokasi strategis dan fasilitas lengkap. Sangat memuaskan!',
                'is_verified' => true,
            ],
            [
                'rating' => 4.0,
                'comment' => 'Lokasi sangat strategis dan akses mudah. Fasilitas cukup lengkap, tapi ada beberapa yang perlu perbaikan.',
                'is_verified' => true,
            ],
            [
                'rating' => 5.0,
                'comment' => 'Pelayanan pemilik sangat baik dan responsif. Properti sesuai dengan deskripsi dan foto.',
                'is_verified' => true,
            ],
            [
                'rating' => 3.0,
                'comment' => 'Properti cukup bagus, tapi ada beberapa fasilitas yang tidak berfungsi dengan baik.',
                'is_verified' => true,
            ],
            [
                'rating' => 4.0,
                'comment' => 'Lingkungan aman dan nyaman. Akses ke transportasi umum sangat mudah.',
                'is_verified' => true,
            ],
        ];

        foreach ($properties as $property) {
            // Generate random number of reviews (1-3) for each property
            $numReviews = rand(1, 3);

            for ($i = 0; $i < $numReviews; $i++) {
                $review = $reviews[array_rand($reviews)];
                $user = $users[array_rand($users instanceof \Illuminate\Database\Eloquent\Collection ? $users->toArray() : $users)];

                PropertyReview::create([
                    'property_id' => $property->id,
                    'user_id' => $user->id,
                    'rating' => $review['rating'],
                    'comment' => $review['comment'],
                    'is_verified' => $review['is_verified'],
                ]);
            }
        }
    }
}
