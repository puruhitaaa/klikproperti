<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Route;
use App\Models\Property;
use Inertia\Response;

class LandingController extends Controller
{
    public function index(): Response
    {
        $popularProperties = Property::with(['owner', 'propertyReviews.user'])
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
                    'image' => $property->getFirstMediaUrl('images'),
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
            // 'canLogin' => Route::has('login'),
            // 'canRegister' => Route::has('register'),
        ]);
    }
}
