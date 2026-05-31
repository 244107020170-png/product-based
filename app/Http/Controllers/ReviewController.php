<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Field;
use App\Models\Review;
use App\Notifications\Owner\OwnerNewReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|numeric|min:0.5|max:5',
            'review' => 'required|string|min:10',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $user = auth()->user();

        $booking = Booking::where('id', $validated['booking_id'])
            ->where('user_id', $user->id)
            ->where('field_id', $validated['field_id'])
            ->whereIn('status', ['confirmed', 'selesai', 'completed'])
            ->first();

        if (!$booking) {
            return back()->with('error', 'Anda belum memiliki booking selesai di lapangan ini.');
        }

        $existingReview = Review::where('user_id', $user->id)
            ->where('field_id', $validated['field_id'])
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan review untuk lapangan ini.');
        }

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('review-photos', 'public');
                if ($path) $photos[] = $path;
            }
        }

        $review = Review::create([
            'user_id' => $user->id,
            'field_id' => $validated['field_id'],
            'booking_id' => $validated['booking_id'],
            'rating' => $validated['rating'],
            'review' => $validated['review'],
            'photos' => $photos,
        ]);

        try {
            $owner = $review->field?->owner;
            if ($owner) {
                $owner->notify(new OwnerNewReview($review));
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal notifikasi owner review: ' . $e->getMessage());
        }

        return back()->with('success', 'Review berhasil dikirim! Terima kasih atas masukan Anda.');
    }

    public function checkEligibility(Field $field)
    {
        $user = auth()->user();

        $existingReview = Review::where('user_id', $user->id)
            ->where('field_id', $field->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'eligible' => false,
                'message' => 'Anda sudah memberikan review untuk lapangan ini.',
            ]);
        }

        $completedBooking = Booking::where('user_id', $user->id)
            ->where('field_id', $field->id)
            ->whereIn('status', ['confirmed', 'selesai', 'completed'])
            ->latest()
            ->first();

        if (!$completedBooking) {
            return response()->json([
                'eligible' => false,
                'message' => 'Anda belum memiliki booking selesai di lapangan ini.',
            ]);
        }

        return response()->json([
            'eligible' => true,
            'booking' => [
                'id' => $completedBooking->id,
                'date' => $completedBooking->date,
            ],
        ]);
    }

    public function checkAnyEligibility()
    {
        $user = auth()->user();

        $reviewedFieldIds = Review::where('user_id', $user->id)->pluck('field_id')->toArray();

        $completedBooking = Booking::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'selesai', 'completed'])
            ->whereNotIn('field_id', $reviewedFieldIds)
            ->latest()
            ->first();

        if (!$completedBooking) {
            return response()->json([
                'eligible' => false,
                'message' => 'Belum ada booking selesai yang perlu direview.',
            ]);
        }

        return response()->json([
            'eligible' => true,
            'booking' => [
                'id' => $completedBooking->id,
                'field_id' => $completedBooking->field_id,
                'date' => $completedBooking->date,
                'field_name' => $completedBooking->field->name ?? 'Lapangan',
            ],
        ]);
    }

    public function latest(Field $field)
    {
        $reviews = Review::with('user')
            ->where('field_id', $field->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'user_name' => $r->user->name,
                    'user_avatar' => $r->user->avatarUrl(),
                    'rating' => $r->rating,
                    'review' => $r->review,
                    'created_at' => $r->created_at->diffForHumans(),
                ];
            });

        return response()->json($reviews);
    }
}
