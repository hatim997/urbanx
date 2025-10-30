<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('dashboard.index');
        } catch (\Throwable $th) {
            Log::error('Dashboard Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function getRoute(Request $request)
    {
        $validated = $request->validate([
            'pickup_lat' => 'required|numeric',
            'pickup_lng' => 'required|numeric',
            'drop_lat' => 'required|numeric',
            'drop_lng' => 'required|numeric',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
        ]);

        $url = "https://router.project-osrm.org/route/v1/driving/{$validated['pickup_lng']},{$validated['pickup_lat']};{$validated['drop_lng']},{$validated['drop_lat']}?overview=full&geometries=geojson";

        $response = Http::get($url)->json();

        if (!isset($response['routes'][0])) {
            return response()->json(['error' => 'No route found'], 400);
        }

        $route = $response['routes'][0];
        $distanceKm = $route['distance'] / 1000;  // meters → km
        $durationMin = $route['duration'] / 60;   // seconds → minutes

        $vehicle = VehicleType::find($validated['vehicle_type_id']);

        // Example fare calculation logic
        $fare = ($vehicle->base_fare ?? 100) + ($distanceKm * 50);

        return response()->json([
            'coordinates' => $route['geometry']['coordinates'],  // polyline points
            'distance_km' => round($distanceKm, 2),
            'duration_min' => round($durationMin),
            'estimated_fare' => round($fare, 0),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
