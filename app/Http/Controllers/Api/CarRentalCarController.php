<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicles;
use App\Models\VehicleTypes;

/**
 * @OA\Parameter(
 *     parameter="AuthorizationHeader",
 *     in="header",
 *     name="Authorization",
 *     required=true,
 *     @OA\Schema(
 *         type="string"
 *     ),
 *     description="Bearer <token>"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Laravel Sanctum authentication",
 * )
 */
class CarRentalCarController extends Controller
{
    /**
     * Get car details
     * @OA\Get (
     *     path="/car-rental/api/v1/cars/{id}",
     *     tags={"cars"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          parameter="AuthorizationHeader",
     *          in="header",
     *          name="Authorization",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          ),
     *          description="Bearer <token>"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *                     @OA\Property(
     *                         property="vehicle-id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="vehicle-name",
     *                         type="string",
     *                         example="Toyota Hybrid"
     *                     ),
     *                     @OA\Property(
     *                         property="transmission",
     *                         type="string",
     *                         example="automatic"
     *                     ),
     *                     @OA\Property(
     *                         property="daily-rate",
     *                         type="float",
     *                         example="3.8"
     *                     ),
     *                     @OA\Property(
     *                         property="seats",
     *                         type="number",
     *                         example="5"
     *                     ),
     *                     @OA\Property(
     *                         property="image",
     *                         type="string",
     *                         example="http://base-url/path/to/image"
     *                     ),
     *                     @OA\Property(
     *                         property="available",
     *                         type="boolean",
     *                         example="true"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     )
     *         )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Car not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Car not found"),
     *          )
     *      )
     * )
     */
    public function getCarDetails($id)
    {
        $vehicle = Vehicles::with('vehicleType')->findOrFail($id);

        if ($vehicle) {
            return response()->json([
                'vehicle-id' => $vehicle->vehicle_id,
                'vehicle-name' => $vehicle->vehicleType->name,
                'transmission' => $vehicle->vehicleType->transmission,
                'daily-rate' => $vehicle->vehicleType->daily_rate,
                'seats' => $vehicle->vehicleType->seats,
                'image' => $vehicle->vehicleType->image,
                'available' => (bool) $vehicle->available,
                'created_at' => $vehicle->created_at,
            ]);
        } else {
            return response()->json(['msg' => 'Car not found'], 404);
        }
    }

    /**
     * Get cars list
     * @OA\Get (
     *     path="/car-rental/api/v1/cars",
     *     tags={"cars"},
     *     @OA\Parameter(
     *          parameter="AuthorizationHeader",
     *          in="header",
     *          name="Authorization",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          ),
     *          description="Bearer <token>"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="vehicle-id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="vehicle-name",
     *                         type="string",
     *                         example="Toyota Hybrid"
     *                     ),
     *                     @OA\Property(
     *                         property="transmission",
     *                         type="string",
     *                         example="automatic"
     *                     ),
     *                     @OA\Property(
     *                         property="daily-rate",
     *                         type="float",
     *                         example="3.8"
     *                     ),
     *                     @OA\Property(
     *                         property="seats",
     *                         type="number",
     *                         example="5"
     *                     ),
     *                     @OA\Property(
     *                         property="image",
     *                         type="string",
     *                         example="http://base-url/path/to/image"
     *                     ),
     *                     @OA\Property(
     *                         property="available",
     *                         type="boolean",
     *                         example="true"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getAllCars()
    {
        $vehicles = Vehicles::with('vehicleType')->get();

        $data = $vehicles->map(function ($vehicle) {
            return [
                'vehicle-id' => $vehicle->vehicle_id,
                'vehicle-name' => $vehicle->vehicleType->name,
                'transmission' => $vehicle->vehicleType->transmission,
                'daily-rate' => $vehicle->vehicleType->daily_rate,
                'seats' => $vehicle->vehicleType->seats,
                'image' => $vehicle->vehicleType->image,
                'available' => (bool) $vehicle->available,
                'created_at' => $vehicle->created_at,
            ];
        });

        return response()->json(['data' => $data]);
    }
}
