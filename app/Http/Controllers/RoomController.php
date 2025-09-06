<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,teacher')->except(['index', 'show']);
    }

    public function index()
    {
        $rooms = Room::orderBy('name')->paginate(15);
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:rooms,code',
            'capacity' => 'required|integer|min:1|max:200',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $room = Room::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'capacity' => $request->capacity,
            'location' => $request->location,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('rooms.show', $room)->with('success', __('app.created_successfully'));
    }

    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }
}


