<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarRentalCarController extends Controller
{
    protected $cars;

    /*public function __construct(cars $cars){
        $this->cars = $cars;
    }*/

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
     *     )
     * )
     */
    public function get($id){
        $cars = $this->cars->getcars($id);
        if($cars){
            return response()->json($cars);
        }
        return response()->json(["msg"=>"cars item not found"],404);
    }

    /**
     * Get cars list
     * @OA\Get (
     *     path="/car-rental/api/v1/cars",
     *     tags={"cars"},
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
    public function gets(){
        $carss = $this->cars->getscars();
        return response()->json(["rows"=>$carss]);
    }

}
