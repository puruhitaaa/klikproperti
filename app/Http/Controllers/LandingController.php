<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index(): Response
    {
        $popularProperties = Property::with(['owner', 'propertyReviews.user', 'propertyType'])
            ->withCount('propertyReviews')
            ->withAvg('propertyReviews', 'rating')
            ->popular()
            ->take(12)
            ->get()
            ->map(function ($property) {
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'description' => $property->description,
                    'price' => (float) $property->price,
                    'type' => $property->type,
                    'location_address' => $property->location_address,
                    'city' => $property->city,
                    'bedrooms' => (int) $property->bedrooms,
                    'bathrooms' => (int) $property->bathrooms,
                    'area' => (float) $property->area,
                    'features' => $property->features,
                    'rating' => (float) ($property->rating ?? 0),
                    'review_count' => (int) $property->review_count,
                    'owner' => [
                        'id' => $property->owner->id,
                        'name' => $property->owner->name,
                    ],
                    'property_type' => [
                        'id' => $property->propertyType->id,
                        'name' => $property->propertyType->name,
                    ],
                    'image' => $property->getFirstMediaUrl('property-images'),
                    'reviews' => $property->propertyReviews->take(3)->map(function ($review) {
                        return [
                            'id' => $review->id,
                            'rating' => (float) $review->rating,
                            'comment' => $review->comment,
                            'user' => [
                                'id' => $review->user->id,
                                'name' => $review->user->name,
                            ],
                            'created_at' => $review->created_at,
                        ];
                    }),
                ];
            });

        return Inertia::render('Landing', [
            'user' => Auth::user(),
            'popularProperties' => $popularProperties,
            'mostSearchedLocations' => $this->getMostSearchedLocations(),
        ]);
    }

    private function getMostSearchedLocations()
    {
        return Property::select('city')
            ->selectRaw('COUNT(*) as property_count')
            ->selectRaw('COALESCE(AVG(rating), 0) as rating')
            ->selectRaw('COALESCE(SUM(review_count), 0) as reviews')
            ->whereNull('deleted_at')
            ->groupBy('city')
            ->orderBy('property_count', 'desc')
            ->take(3)
            ->get()
            ->map(function ($location) {
                return [
                    'city' => $location->city,
                    'property_count' => (int)$location->property_count,
                    'rating' => round((float)$location->rating, 1),
                    'reviews' => (int)$location->reviews,
                    'image' => $this->getCityImage($location->city),
                ];
            });
    }

    private function getCityImage($city)
    {
        $cityImages = [
            'Bandung' => '/images/cities/bandung.jpeg',
            'Denpasar' => '/images/cities/denpasar.jpeg',
            'Jakarta' => '/images/cities/jakarta.jpeg',
            'Makassar' => '/images/cities/makassar.jpeg',
            'Medan' => '/images/cities/medan.jpeg',
            'Padang' => '/images/cities/padang.jpeg',
            'Palembang' => '/images/cities/palembang.jpeg',
            'Semarang' => '/images/cities/semarang.jpeg',
            'Surabaya' => '/images/cities/surabaya.jpeg',
            'Yogyakarta' => '/images/cities/yogyakarta.jpeg',
        ];

        return $cityImages[$city] ?? $cityImages['Jakarta'];
    }
}
