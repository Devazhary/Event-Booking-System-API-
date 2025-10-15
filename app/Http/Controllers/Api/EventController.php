<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    
    public function index()
    {
        $events = Event::with('category')->paginate(5);
        return EventResource::collection($events);
    }

    
    public function store(EventRequest $request)
    {
        $data = $request->validated();
        $event = Event::create($data);
        return new EventResource($event);
    }

   
    public function show(Event $event)
    {
        return new EventResource($event);
    }

    public function update(EventRequest $request,Event $event)
    {
        //validate
        $data = $request->validated();
        //update
        $event->update($data);
        //return response
        return new EventResource($event);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json([
            'message' => 'Event Deleted Successfully'
        ], 200);
    }

    public function toggle(Event $event)
    {
        $event->is_active = !$event->is_active;
        $event->save();
        return new EventResource($event);
    }
}
