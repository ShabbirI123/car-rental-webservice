<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RicorocksDigitalAgency\Soap\Facades\Soap;

class CarRentalBookingController extends Controller
{
    protected $bookings;

    /*public function __construct(bookings $bookings){
        $this->bookings = $bookings;
    }*/
    /**
     * @OA\Schema(
     *     schema="Booking",
     *     required={"booking_id", "car_id", "user_id", "start_date", "end_date", "created_at", "updated_at"},
     *     @OA\Property(property="booking_id", type="integer", example=1),
     *     @OA\Property(property="car_id", type="integer", example=1),
     *     @OA\Property(property="user_id", type="integer", example=1),
     *     @OA\Property(property="start_date", type="string", format="date-time", example="2023-04-01T00:00:00.000000Z"),
     *     @OA\Property(property="end_date", type="string", format="date-time", example="2023-04-05T00:00:00.000000Z"),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z")
     * )
     */

    /**
     * Create a car booking
     * @OA\Post(
     *     path="/car-rental/api/v1/bookings",
     *     tags={"bookings"},
     *     @OA\Parameter(
     *          in="header",
     *          name="token",
     *          required=true,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="token",
     *                  type="string",
     *                  example="10293182301230123"
     *              )
     *          )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="car_id", type="integer", example=2),
     *             @OA\Property(property="start_date", type="string", example="2023-04-01"),
     *             @OA\Property(property="end_date", type="string", example="2023-04-05")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="created",
     *         @OA\JsonContent(
     *              @OA\Property(property="booking_id", type="integer", example=1),
     *              @OA\Property(property="car_id", type="integer", example=1),
     *              @OA\Property(property="user_id", type="integer", example=1),
     *              @OA\Property(property="start_date", type="string", format="date-time", example="2023-04-01T00:00:00.000000Z"),
     *              @OA\Property(property="end_date", type="string", format="date-time", example="2023-04-05T00:00:00.000000Z"),
     *              @OA\Property(property="created_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z"),
     *              @OA\Property(property="updated_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Unauthorized: Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Invalid credentials"),
     *          )
     *      )
     * )
     */
    public function createBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:customers,customer_id',
            'car_id' => 'required|integer|exists:vehicles,vehicle_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $booking = new Booking([
            'car_id' => $request->car_id,
            'user_id' => $request->user_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        $booking->save();

        return response()->json($booking, 201);
    }

    /**
     * Update a car booking
     * @OA\Put(
     *     path="/car-rental/api/v1/bookings/{id}",
     *     tags={"bookings"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *          in="header",
     *          name="token",
     *          required=true,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="token",
     *                  type="string",
     *                  example="10293182301230123"
     *              )
     *          )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="start_date", type="string", example="2023-04-01"),
     *             @OA\Property(property="end_date", type="string", example="2023-04-05")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="booking_id", type="integer", example=1),
     *              @OA\Property(property="car_id", type="integer", example=1),
     *              @OA\Property(property="user_id", type="integer", example=1),
     *              @OA\Property(property="start_date", type="string", format="date-time", example="2023-04-01T00:00:00.000000Z"),
     *              @OA\Property(property="end_date", type="string", format="date-time", example="2023-04-05T00:00:00.000000Z"),
     *              @OA\Property(property="created_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z"),
     *              @OA\Property(property="updated_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Unauthorized: Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Invalid credentials"),
     *          )
     *      )
     * )
     */
    public function updateBooking(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $booking = Booking::findOrFail($id);

        $booking->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json($booking, 200);
    }

    /**
     * Get a specific car booking
     * @OA\Get(
     *     path="/car-rental/api/v1/bookings/{id}",
     *     tags={"bookings"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *          in="header",
     *          name="token",
     *          required=true,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="token",
     *                  type="string",
     *                  example="10293182301230123"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="booking_id", type="integer", example=1),
     *              @OA\Property(property="car_id", type="integer", example=1),
     *              @OA\Property(property="user_id", type="integer", example=1),
     *              @OA\Property(property="start_date", type="string", format="date-time", example="2023-04-01T00:00:00.000000Z"),
     *              @OA\Property(property="end_date", type="string", format="date-time", example="2023-04-05T00:00:00.000000Z"),
     *              @OA\Property(property="created_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z"),
     *              @OA\Property(property="updated_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Unauthorized: Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Invalid credentials"),
     *          )
     *      )
     * )
     */
    public function getBooking($id)
    {
        $booking = Booking::findOrFail($id);

        return response()->json($booking, 200);
    }

    /**
     * Get all bookings for a specific user
     * @OA\Get(
     *     path="/car-rental/api/v1/users/{user_id}/bookings",
     *     tags={"bookings"},
     *     @OA\Parameter(
     *         in="path",
     *         name="user_id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *          in="header",
     *          name="token",
     *          required=true,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="token",
     *                  type="string",
     *                  example="10293182301230123"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="bookings",
     *                 @OA\Items(
     *                      @OA\Property(property="booking_id", type="integer", example=1),
     *                      @OA\Property(property="user_id", type="integer", example=1),
     *                      @OA\Property(property="car_id", type="integer", example=1),
     *                      @OA\Property(property="start_date", type="string", example="2023-01-01"),
     *                      @OA\Property(property="end_date", type="string", example="2023-01-07"),
     *                      @OA\Property(property="created_at", type="string", example="2023-01-01T00:00:00.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2023-01-01T00:00:00.000000Z"),)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Unauthorized: Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Invalid credentials"),
     *          )
     *      )
     * )
     */
    public function getUserBookings($user_id)
    {
        $bookings = Booking::where('user_id', $user_id)->get();

        return response()->json(['bookings' => $bookings], 200);
    }

    /**
     * Delete a car booking
     * @OA\Delete(
     *     path="/car-rental/api/v1/bookings/{id}",
     *     tags={"bookings"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *          in="header",
     *          name="token",
     *          required=true,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="token",
     *                  type="string",
     *                  example="10293182301230123"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="deleted"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Unauthorized: Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Invalid credentials"),
     *          )
     *      )
     * )
     */
    public function deleteBooking($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->delete();

        return response()->json(null, 204);
    }
}
