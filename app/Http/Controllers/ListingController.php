<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['owner', 'propertyReviews.user', 'propertyType'])
            ->withCount('propertyReviews')
            ->withAvg('propertyReviews', 'rating')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->min_price, fn ($query, $price) => $query->where('price', '>=', $price))
            ->when($request->max_price, fn ($query, $price) => $query->where('price', '<=', $price))
            ->when($request->property_type, fn ($query, $type) => $query->where('property_type_id', $type))
            ->when($request->city, fn ($query, $city) => $query->where('city', $city))
            ->when($request->bedrooms, fn ($query, $beds) => $query->where('bedrooms', $beds))
            ->when($request->bathrooms, fn ($query, $baths) => $query->where('bathrooms', $baths))
            ->when($request->min_area, fn ($query, $area) => $query->where('area', '>=', $area))
            ->when($request->max_area, fn ($query, $area) => $query->where('area', '<=', $area))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->features, function ($query, $features) {
                foreach (explode(',', $features) as $feature) {
                    $query->whereJsonContains('features', $feature);
                }
            })
            ->when($request->sort, function ($query, $sort) {
                match ($sort) {
                    'price_asc' => $query->orderBy('price', 'asc'),
                    'price_desc' => $query->orderBy('price', 'desc'),
                    'rating' => $query->orderBy('rating', 'desc'),
                    'reviews' => $query->orderBy('review_count', 'desc'),
                    'latest' => $query->latest(),
                    default => $query->latest()
                };
            }, fn ($query) => $query->latest());

        $paginatedProperties = $query->paginate(12)->appends($request->query());

        $properties = [
            'data' => collect($paginatedProperties->items())->map(fn ($property) => [
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
                'rating' => (float) ($property->property_reviews_avg_rating ?? 0),
                'review_count' => (int) $property->property_reviews_count,
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
            ]),
            'current_page' => $paginatedProperties->currentPage(),
            'last_page' => $paginatedProperties->lastPage(),
        ];

        $propertyTypes = PropertyType::all();
        $cities = Property::distinct()->pluck('city');

        return Inertia::render('Listings/Index', [
            'properties' => $properties,
            'propertyTypes' => $propertyTypes,
            'cities' => $cities,
            'filters' => $request->only([
                'search',
                'min_price',
                'max_price',
                'property_type',
                'city',
                'bedrooms',
                'bathrooms',
                'min_area',
                'max_area',
                'status',
                'features',
                'sort'
            ])
        ]);
    }
}
