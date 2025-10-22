<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DriverVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DriverDetailsController extends Controller
{
    public function getVehicleDetails(Request $request)
    {
        try {
            $user = $request->user();

            $driverVehicle = DriverVehicle::where('driver_id', $user->id)->first();

            $data = null;

            if ($driverVehicle) {
                $images = [];
                if (!empty($driverVehicle->vehicle_images)) {
                    $decodedImages = json_decode($driverVehicle->vehicle_images, true);
                    if (is_array($decodedImages)) {
                        foreach ($decodedImages as $img) {
                            $images[] = url($img);
                        }
                    }
                }

                // Prepare response data
                $data = [
                    'vehicle_name' => $driverVehicle->vehicle_name,
                    'vehicle_make' => $driverVehicle->vehicle_make,
                    'vehicle_model' => $driverVehicle->vehicle_model,
                    'vehicle_color' => $driverVehicle->vehicle_color,
                    'vehicle_year' => $driverVehicle->vehicle_year,
                    'vehicle_plate_number' => $driverVehicle->vehicle_plate_number,
                    'vehicle_images' => $images,
                ];
            }


            return response()->json([
                'vehicle' => $data,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('API Vehicle Details failed', ['error' => $th->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateVehicleDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_name' => 'required|string',
            'vehicle_make' => 'nullable|string',
            'vehicle_model' => 'nullable|string',
            'vehicle_color' => 'nullable|string',
            'vehicle_year' => 'nullable|string',
            'vehicle_plate_number' => 'nullable|string',
            'vehicle_images' => 'nullable|array',
            'vehicle_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max_size',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = $request->user();

            $driverVehicle = DriverVehicle::where('driver_id', $user->id)->first();

            if (!$driverVehicle) {
                $driverVehicle = new DriverVehicle();
                $driverVehicle->driver_id = $user->id;
            }

            $driverVehicle->vehicle_name = $request->input('vehicle_name');
            $driverVehicle->vehicle_make = $request->input('vehicle_make');
            $driverVehicle->vehicle_model = $request->input('vehicle_model');
            $driverVehicle->vehicle_color = $request->input('vehicle_color');
            $driverVehicle->vehicle_year = $request->input('vehicle_year');
            $driverVehicle->vehicle_plate_number = $request->input('vehicle_plate_number');

            // Keep old images if exist
            $images = [];
            if (!empty($driverVehicle->vehicle_images)) {
                $images = json_decode($driverVehicle->vehicle_images, true);
            }

            // Upload and append new images
            if ($request->hasFile('vehicle_images')) {
                foreach ($request->file('vehicle_images') as $image) {
                    $path = $image->store('uploads/vehicle-images', 'public');
                    $images[] = $path;
                }
            }

            $driverVehicle->vehicle_images = json_encode($images);
            $driverVehicle->save();

            return response()->json([
                'message' => 'Vehicle details updated successfully',
                'vehicle' => [
                    'vehicle_name' => $driverVehicle->vehicle_name,
                    'vehicle_make' => $driverVehicle->vehicle_make,
                    'vehicle_model' => $driverVehicle->vehicle_model,
                    'vehicle_color' => $driverVehicle->vehicle_color,
                    'vehicle_year' => $driverVehicle->vehicle_year,
                    'vehicle_plate_number' => $driverVehicle->vehicle_plate_number,
                    'vehicle_images' => $images,
                ]
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('API Update Vehicle Details failed', ['error' => $th->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
