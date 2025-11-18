<?php

namespace App\Http\Controllers\API\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\Ride;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RideController extends Controller
{
    public function calculateDistanceFare(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type_id' => 'nullable|exists:vehicle_types,id',
            'distance_km' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Fetch vehicle type pricing details
            $vehicleType = VehicleType::find($request->vehicle_type_id);
            if (!$vehicleType) {
                $baseFare = 5.00;
            }else{
                $baseFare = $vehicleType->base_fare;
            }

            // Calculate fare components
            $totalFare = $request->distance_km * $baseFare;

            return response()->json([
                'total_fare' => $totalFare,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('API Calculate Ride Fare failed', ['error' => $th->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function promoCodeApply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'promo_code' => 'required|string|exists:promo_codes,code',
            'total_fare' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $promoCode = PromoCode::where('code', $request->promo_code)
                ->where('is_active', 'active')
                ->where('valid_from', '<=', now())
                ->where('valid_until', '>=', now())
                ->first();

            if (!$promoCode) {
                return response()->json([
                    'message' => 'Invalid or expired promo code.'
                ], Response::HTTP_BAD_REQUEST);
            }
            // CHECK PER RIDE LIMIT
            $rideUsedCount = Ride::where('promo_code_id', $promoCode->id)->count();
            if ($rideUsedCount >= $promoCode->usage_limit) {
                return response()->json([
                    'message' => 'Promo code usage limit reached for this ride.'
                ], Response::HTTP_BAD_REQUEST);
            }

            // CHECK PER USER LIMIT
            $userUsedCount = Ride::where('passenger_id', auth()->id())
                ->where('promo_code_id', $promoCode->id)
                ->count();

            if ($userUsedCount >= $promoCode->usage_limit_per_user) {
                return response()->json([
                    'message' => 'Promo code usage limit reached for this user.'
                ], Response::HTTP_BAD_REQUEST);
            }

            $promoCodeData = [
                'id' => $promoCode->id,
                'name' => $promoCode->name,
                'code' => $promoCode->code,
                'discount_percentage' => $promoCode->discount_percentage,
            ];

            if ($promoCode->discount_percentage > 0) {
                $discountAmount = ($promoCode->discount_percentage / 100) * $request->total_fare;
                $promoCodeData['discount_amount'] = $discountAmount;
                $promoCodeData['total_fare'] = $request->total_fare - $discountAmount;
            } else {
                $promoCodeData['discount_amount'] = 0;
                $promoCodeData['total_fare'] = $request->total_fare;
            }

            return response()->json([
                'promo_code' => $promoCodeData,
                'message' => 'Promo code is valid.'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('API Apply Promo Code failed', ['error' => $th->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function requestRide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'promo_code_id' => 'nullable|exists:promo_codes,id',
            'pickup_latitude' => 'required|string',
            'pickup_longitude' => 'required|string',
            'dropoff_latitude' => 'nullable|string',
            'dropoff_longitude' => 'nullable|string',
            'distance_km' => 'nullable|string',
            'duration_minutes' => 'nullable|string',
            'subtotal' => 'nullable|string',
            'discount_amount' => 'nullable|string',
            'total_fare' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = $request->user();

            $ride = new Ride();
            $ride->passenger_id = $user->id;
            $ride->vehicle_type_id = $request->vehicle_type_id;
            $ride->promo_code_id = $request->promo_code_id;
            $ride->pickup_latitude = $request->pickup_latitude;
            $ride->pickup_longitude = $request->pickup_longitude;
            $ride->dropoff_latitude = $request->dropoff_latitude;
            $ride->dropoff_longitude = $request->dropoff_longitude;
            $ride->distance_km = $request->distance_km;
            $ride->duration_minutes = $request->duration_minutes;
            $ride->subtotal = $request->subtotal;
            $ride->discount_amount = $request->discount_amount;
            $ride->total_fare = $request->total_fare;
            $ride->requested_at = now();
            $ride->status = 'requested';
            $ride->save();

            return response()->json([
                'message' => 'Ride requested successfully!',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('API Store Ride failed', ['error' => $th->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
