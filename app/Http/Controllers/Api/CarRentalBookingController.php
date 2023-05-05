<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VehicleTypes;
use Illuminate\Http\Request;
use App\Models\Invoices;
use App\Models\Rentals;
use App\Models\Vehicles;
use Carbon\Carbon;

class CarRentalBookingController extends Controller
{
    /**
     * Create a car booking
     * @OA\Post(
     *     path="/car-rental/api/v1/bookings",
     *     tags={"bookings"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="vehicle_id", type="integer", example=2),
     *             @OA\Property(property="currency", type="string", example="Euro"),
     *             @OA\Property(property="amount", type="double", example=40.5),
     *             @OA\Property(property="start_date", type="string", example="2023-04-01"),
     *             @OA\Property(property="end_date", type="string", example="2023-04-05")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="created",
     *         @OA\JsonContent(
     *              @OA\Property(property="booking_id", type="integer", example=1),
     *              @OA\Property(property="vehicle_id", type="integer", example=1),
     *              @OA\Property(property="user_id", type="integer", example=1),
     *              @OA\Property(property="start_date", type="string", format="date-time", example="2023-04-01T00:00:00.000000Z"),
     *              @OA\Property(property="end_date", type="string", format="date-time", example="2023-04-05T00:00:00.000000Z"),
     *              @OA\Property(property="created_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z"),
     *              @OA\Property(property="updated_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Unauthorized: Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Invalid credentials"),
     *          )
     *      )
     * )
     */
    public function createBooking(Request $request)
    {
        $price = 0;

        // Validate customer_id and vehicle_id
        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:customers,customer_id',
            'vehicle_id' => 'required|integer|exists:vehicles,vehicle_id',
            'currency' => 'required|string',
            'amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validatedData['currency'] != 'USD'):
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://3.72.41.7:8080/?wsdl',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:your="CurrencyConverter">
   <soapenv:Header/>
   <soapenv:Body>
      <your:convert>
         <your:api_key>apikey1</your:api_key>
         <your:base_currency>'.$validatedData['currency'].'</your:base_currency>
         <your:target_currency>USD</your:target_currency>
         <your:amount>'.$validatedData['amount'].'</your:amount>
      </your:convert>
   </soapenv:Body>
</soapenv:Envelope>',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/xml; charset=utf-8',
                    'SOAPAction: CurrencyConverter/convert'
                ),
            ));

            $price = curl_exec($curl);

            curl_close($curl);
        endif;

        // Create Invoice
        $invoice = new Invoices([
            'customer_id' => $validatedData['user_id'],
            'total_amount' => $validatedData['amount'],
            'original_currency' => 'US-Dollar',
            'total_amount_selected_currency' => (double) $price,
            'selected_currency' => $validatedData['currency'],
            'invoice_date' => now(),
            'payment_status' => 'Paid',
        ]);

        $invoice->save();

        // Get the created invoice ID
        $invoice_id = $invoice->invoice_id;

        // Get the vehicle_id and change the available status to false
        $vehicle = Vehicles::findOrFail($validatedData['vehicle_id']);
        $vehicle->available = false;
        $vehicle->save();

        // Create Rental
        $rental = new Rentals([
            'customer_id' => $validatedData['user_id'],
            'vehicle_id' => $validatedData['vehicle_id'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'total_days' => now()->diffInDays(Carbon::parse($validatedData['end_date'])),
            'invoice_id' => $invoice_id,
        ]);

        $rental->save();

        // Return response if everything is successful
        return response()->json([
            'booking_id' => $rental->rental_id,
            'vehicle_id' => $validatedData['vehicle_id'],
            'user_id' => $validatedData['user_id'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'created_at' => $rental->created_at->toDateTimeString(),
            'updated_at' => $rental->updated_at->toDateTimeString(),
        ], 201);
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
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="booking", type="object",
     *                  @OA\Property(property="rental_id", type="integer", example=1),
     *                  @OA\Property(property="customer_id", type="integer", example=1),
     *                  @OA\Property(property="vehicle_id", type="integer", example=1),
     *                  @OA\Property(property="start_date", type="string", format="date", example="2023-04-01"),
     *                  @OA\Property(property="end_date", type="string", format="date", example="2023-04-05"),
     *                  @OA\Property(property="total_days", type="integer", example=4),
     *                  @OA\Property(property="invoice_id", type="integer", example=1),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z"),
     *              ),
     *              @OA\Property(property="invoice", type="object",
     *                  @OA\Property(property="invoice_id", type="integer", example=1),
     *                  @OA\Property(property="customer_id", type="integer", example=1),
     *                  @OA\Property(property="total_amount", type="number", format="float", example=500.00),
     *                  @OA\Property(property="original_currency", type="string", example="USD"),
     *                  @OA\Property(property="total_amount_selected_currency", type="number", format="float", example=445.00),
     *                  @OA\Property(property="selected_currency", type="string", example="EUR"),
     *                  @OA\Property(property="invoice_date", type="string", format="date", example="2023-03-18"),
     *                  @OA\Property(property="payment_status", type="string", example="Paid"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z"),
     *              ),
     *              @OA\Property(property="vehicle", type="object",
     *                  @OA\Property(property="vehicle_id", type="integer", example=1),
     *                  @OA\Property(property="vehicle_type_id", type="integer", example=1),
     *                  @OA\Property(property="location_id", type="integer", example=1),
     *                  @OA\Property(property="available", type="boolean", example=true),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2023-03-18T09:25:53.000000Z"),
     *              )
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
        $booking = Rentals::findOrFail($id);

        // Fetch the invoice data using the invoice_id
        $invoice = Invoices::findOrFail($booking->invoice_id);

        // Fetch the vehicle data using the vehicle_id
        $vehicle = Vehicles::findOrFail($booking->vehicle_id);

        // Combine the booking, invoice, and vehicle data into a single array
        $booking_data = [
            'booking' => $booking,
            'invoice' => $invoice,
            'vehicle' => $vehicle
        ];

        return response()->json($booking_data, 200);
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
     *                      @OA\Property(property="vehicle_id", type="integer", example=1),
     *                      @OA\Property(property="start_date", type="string", example="2023-01-01"),
     *                      @OA\Property(property="end_date", type="string", example="2023-01-07"),
     *                      @OA\Property(property="created_at", type="string", example="2023-01-01T00:00:00.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2023-01-01T00:00:00.000000Z"),
     *                  ),
     *                  @OA\Items(
     *                     @OA\Property(property="vehicle-id",type="number",example="1"),
     *                     @OA\Property(property="vehicle-name",type="string",example="Toyota Hybrid"),
     *                     @OA\Property(property="transmission",type="string",example="automatic"),
     *                     @OA\Property(property="daily-rate",type="float",example="3.8"),
     *                     @OA\Property(property="seats",type="number",example="5"),
     *                     @OA\Property(property="price",type="number",example="15"),
     *                     @OA\Property(property="image",type="string",example="http://base-url/path/to/image"),
     *                     @OA\Property(property="available",type="boolean",example="true"),
     *                     @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *                  ),
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
        $data = [];
        $bookings = Rentals::where('customer_id', $user_id)->get();

        foreach ($bookings as $booking):
            $vehicle = Vehicles::where('vehicle_id', $booking->vehicle_id)->get();
            $vehicle_type = VehicleTypes::where('vehicle_type_id', $vehicle[0]->vehicle_type_id)->get();
            array_push($data, [
               'booking' => $booking,
               'vehicle_type' => $vehicle_type[0]
            ]);
        endforeach;

        return response()->json(['bookings' => $data], 200);
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
     *     @OA\Response(
     *          response=204,
     *          description="Canceled",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Deletion success"),
     *          )
     *      ),
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
        $booking = Rentals::findOrFail($id);

        // Get the vehicle_id and change the available status to true
        $vehicle = Vehicles::findOrFail($booking->vehicle_id);
        $vehicle->available = true;
        $vehicle->save();

        $booking->delete();

        return response()->json(['msg' => 'Deletion success'], 204);
    }


    /**
     * Cancel a car booking
     * @OA\Post(
     *     path="/car-rental/api/v1/bookings/{id}",
     *     tags={"bookings"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(
     *          response=204,
     *          description="Canceled",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Cancellation success"),
     *          )
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="Unauthorized: Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Invalid credentials"),
     *          )
     *      )
     * )
     */
    public function cancelBooking($id)
    {
        $booking = Rentals::findOrFail($id);

        // Get the vehicle_id and change the available status to true
        $vehicle = Vehicles::findOrFail($booking->vehicle_id);
        $vehicle->available = true;
        $vehicle->save();

        return response()->json(['msg' => 'Cancellation success'], 204);
    }
}
