<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BookingResource;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::paginate(5);
        return BookingResource::collection($bookings);
    }

    public function show(Booking $booking)
    {
        return new BookingResource($booking);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $event = Event::findOrFail($data['event_id']);

        if ($event->bookings()->where('user_id', $data['user_id'])->exists()) {
            return response()->json([
                'message' => 'You have already booked this event before',
            ], 422);
        }

        if ($event->available_seats <= 0) {
            return response()->json([
                'message' => 'There are no more available seats',
            ], 422);
        }

        if (!$event->is_active) {
            return response()->json([
                'message' => 'Event is no longer active',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $booking = Booking::create($data);

            DB::commit();

            return response()->json([
                'message' => 'Successfully booked',
                'booking_info' => new BookingResource($booking),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Booking failed, please try again later.',
            ], 500);
        }
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        if($data['status'] === 'confirmed')
        {
            $event = Event::findOrFail($booking->event_id);
            if ($event->available_seats <= 0) {
                return response()->json([
                    'message' => 'There are no more available seats',
                ], 422);
            }
            $booking->update(['status' => 'confirmed']);
            $event->decrement('available_seats');
        }elseif($data['status'] === 'cancelled')
        {
            $event = Event::findOrFail($booking->event_id);
            $booking->update(['status' => 'cancelled']);
            $event->increment('available_seats');
        }

        return response()->json([
            'message' => 'Successfully updated',
            'booking_info' => new BookingResource($booking),
        ], 200);
    }

    public function toggle(Booking $booking)
    {
        $booking->is_active = !$booking->is_active;
        $booking->save();
        return new BookingResource($booking);
    }
}
