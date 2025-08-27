<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Restaurant $restaurant, Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|unique:restaurants|string|max:255',
            'address'     => 'required|string|max:500',
            'phone'       => 'nullable|string|max:20',
        ]);

        if (Restaurant::where('owner_id', auth()->id())->exists()) {
            
            return response()->json([
                'success' => false,
                'message' => 'Each owner can only have one restaurant.'
            ], 403);
        }

        $data['owner_id'] = auth()->id();

        $restaurant = Restaurant::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Restaurant created successfully.',
            'restaurant' => $restaurant
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
