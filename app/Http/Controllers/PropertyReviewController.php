<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // Check if user has already reviewed this property
        $existingReview = $property->propertyReviews()
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'You have already reviewed this property'
            ], 422);
        }

        $review = $property->propertyReviews()->create([
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return response()->json([
            'message' => 'Review submitted successfully',
            'review' => $review->load('user')
        ]);
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, Property $property, PropertyReview $review)
    {
        // Check if the review belongs to the authenticated user
        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $review->update($validated);

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review->fresh()->load('user')
        ]);
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Property $property, PropertyReview $review)
    {
        // Check if the review belongs to the authenticated user
        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully'
        ]);
    }

    /**
     * Get reviews for a property
     */
    public function index(Property $property)
    {
        $reviews = $property->propertyReviews()
            ->with('user')
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }
}
