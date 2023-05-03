<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customers;
use Illuminate\Support\Facades\Hash;

class CarRentalUserController extends Controller
{
    /**
     * Authenticate user
     * @OA\Post (
     *     path="/car-rental/api/v1/users/login",
     *     tags={"users"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="username",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "username":"johndoe123",
     *                     "password":"password123"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="firstname", type="string", example="John"),
     *              @OA\Property(property="lastname", type="string", example="Doe"),
     *              @OA\Property(property="username", type="string", example="johndoe123"),
     *              @OA\Property(property="token", type="string", example="10293182301230123"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Unauthorized: Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Invalid credentials"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="User not found"),
     *          )
     *      )
     * )
     */
    public function authenticateUser(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = Customers::where('username', $validatedData['username'])->first();

        if ($user) {
            if (Hash::check($validatedData['password'], $user->password)) {
                $token = $user->createToken('authToken')->plainTextToken;

                return response()->json([
                    'id' => $user->customer_id,
                    'firstname' => $user->first_name,
                    'lastname' => $user->last_name,
                    'username' => $user->username,
                    'token' => $token,
                ], 200);
            } else {
                return response()->json(['msg' => 'Invalid credentials'], 403);
            }
        } else {
            return response()->json(['msg' => 'User not found'], 404);
        }
    }

    /**
     * Create user
     * @OA\Post (
     *     path="/car-rental/api/v1/users",
     *     tags={"users"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="firstname",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="lastname",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="username",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "firstname":"John",
     *                     "lastname":"Doe",
     *                     "username":"johndoe123",
     *                     "password":"password123"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Registration successful")
     *          )
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="conflict",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="User already exists")
     *          )
     *      )
     * )
     */
    public function createUser(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string|unique:customers',
            'password' => 'required|string',
        ]);

        try {
            if (DB::table('customers')->where('username', $validatedData['username'])->doesntExist()) {
                $user = new Customers;
                $user->first_name = $validatedData['firstname'];
                $user->last_name = $validatedData['lastname'];
                $user->username = $validatedData['username'];
                $user->password = bcrypt($validatedData['password']);
                $user->save();

                return response()->json(['msg' => 'Registration successful'], 201);
            } else {
                return response()->json(['msg' => 'User already exists'], 409);
            }
        } catch (Exception $exception) {
            return response()->json(['msg' => $exception->getMessage()], 500);
        }
    }

    /**
     * Get user data
     * @OA\Get  (
     *     path="/car-rental/api/v1/users/{id}",
     *     tags={"users"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *                     @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="firstname",
     *                         type="string",
     *                         example="John"
     *                     ),
     *                     @OA\Property(
     *                         property="lastname",
     *                         type="string",
     *                         example="Doe"
     *                     ),
     *                     @OA\Property(
     *                         property="username",
     *                         type="string",
     *                         example="johndoe123"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     )
     *         )
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="User not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="User not found"),
     *          )
     *      )
     * )
     */
    public function getUserData($id)
    {
        $user = Customers::find($id);

        if (!$user) {
            return response()->json(['msg' => 'User not found'], 404);
        }

        return response()->json([
            'id' => $user->customer_id,
            'firstname' => $user->first_name,
            'lastname' => $user->last_name,
            'username' => $user->username,
            'created_at' => $user->created_at,
        ]);
    }
}
