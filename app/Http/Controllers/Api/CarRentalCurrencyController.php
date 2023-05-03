<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarRentalCurrencyController extends Controller
{
    /**
     * Get currency amount
     * @OA\Post(
     *     path="/car-rental/api/v1/currencies",
     *     tags={"currencies"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="currency", type="string", example="Euro"),
     *             @OA\Property(property="amount", type="double", example=40.5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="created",
     *         @OA\JsonContent(
     *              @OA\Property(property="currency_amount", type="double", example=40.5)
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
    public function getCurrencyAmount(Request $request)
    {
        $price = 0;

        $validatedData = $request->validate([
            'currency' => 'required|string',
            'amount' => 'required|numeric'
        ]);

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
         <your:base_currency>USD</your:base_currency>
         <your:target_currency>' . $validatedData['currency'] . '</your:target_currency>
         <your:amount>' . $validatedData['amount'] . '</your:amount>
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

        // Return response if everything is successful
        return response()->json([
            'currency_amount' => $price
        ], 201);
    }

    /**
     * Get currency list
     * @OA\GET(
     *     path="/car-rental/api/v1/currencies",
     *     tags={"currencies"},
     *     @OA\Response(
     *         response=201,
     *         description="created",
     *         @OA\JsonContent(
     *              @OA\Property(property="currency_amount", type="double", example=40.5)
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
    public function getCurrencyList()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://3.72.41.7:8080/?wsdl=null',
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
      <your:available_currencies>
         <your:api_key>apikey1</your:api_key>
      </your:available_currencies>
   </soapenv:Body>
</soapenv:Envelope>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml; charset=utf-8',
                'SOAPAction: CurrencyConverter/available_currencies'
            ),
        ));

        $list = curl_exec($curl);

        curl_close($curl);

        // Return response if everything is successful
        return response()->json([
            'currency_amount' => $list
        ], 201);
    }
}
