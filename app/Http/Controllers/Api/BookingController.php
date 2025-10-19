<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
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
            'user_id' => 'required|exists:users,id'
        ]);

        $event = Event::findOrFail($data['event_id']);

        if($event->bookings()->where('event_id', $data['event_id'])->where('user_id', $data['user_id']))
        {
            return response()->json([
                'message' => 'Your are already booked this event before',
            ], 200);
        }

        if($event->available_seats <= 0)
        {
            return response()->json([
                'message' => 'There is no more available seats',
            ], 200);
        }

        if(!$event->is_active)
        {
            return response()->json([
                'message' => 'Event is no longer active',
            ], 200);
        }

        try
        {
            DB::beginTransaction();
            $booking = Booking::create($data);
            $event->decrement('available_seats');
            DB::commit();
            return response()->json([
                'message' => 'Successfully Booked',
                'booking_info' => new BookingResource($booking),
            ], 200);

        }catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'message' => 'Booked Failed',
            ], 200);
        }

    }

}
