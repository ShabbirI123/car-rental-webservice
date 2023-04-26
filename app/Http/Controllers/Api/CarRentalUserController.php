<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CarRentalUserController extends Controller
{
    protected $users;

    /*public function __construct(users $users){
        $this->users = $users;
    }*/

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
     *                     "email":"johndoe123",
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
     * Modify user data
     * @OA\Put (
     *     path="/car-rental/api/v1/users/{id}",
     *     tags={"users"},
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
     *                     "firstname":"JohnNew",
     *                     "lastname":"DoeNew",
     *                     "email":"johndoe123",
     *                     "password":"password123"
     *                }
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="firstname", type="string", example="JohnNew"),
     *              @OA\Property(property="lastname", type="string", example="DoeNew"),
     *              @OA\Property(property="username", type="string", example="johndoe123"),
     *         )
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Unauthorized: Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Invalid credentials"),
     *          )
     *      )
     * )
     */
    public function modifyUserData($id, Request $request)
    {
        if (!Auth::user() || Auth::user()->customer_id != $id) {
            return response()->json(['msg' => 'Invalid credentials'], 403);
        }

        $validatedData = $request->validate([
            'firstname' => 'sometimes|required|string',
            'lastname' => 'sometimes|required|string',
            'username' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('customers')->ignore(Auth::user()->customer_id, 'customer_id'),
            ],
            'password' => 'sometimes|required|string',
        ]);

        $user = Auth::user();
        $user->update([
            'first_name' => $validatedData['firstname'] ?? $user->first_name,
            'last_name' => $validatedData['lastname'] ?? $user->last_name,
            'username' => $validatedData['username'] ?? $user->username,
            'password' => isset($validatedData['password']) ? bcrypt($validatedData['password']) : $user->password,
        ]);

        return response()->json([
            'id' => $user->customer_id,
            'firstname' => $user->first_name,
            'lastname' => $user->last_name,
            'username' => $user->username,
        ], 200);
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
     *     )
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

    /**
     * Get all users
     * @OA\Get  (
     *     path="/car-rental/api/v1/users",
     *     tags={"users"},
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
     *      @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(
     *                     type="object",
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
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getAllUsers()
    {
        $users = Customers::all();

        $userList = $users->map(function ($user) {
            return [
                'id' => $user->customer_id,
                'firstname' => $user->first_name,
                'lastname' => $user->last_name,
                'username' => $user->username,
                'created_at' => $user->created_at,
            ];
        });

        return response()->json(['data' => $userList]);
    }

    /**
     * Delete user
     * @OA\Delete (
     *     path="/car-rental/api/v1/users/deletion/{id}",
     *     tags={"users"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
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
     *         response=204,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="delete users success")
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
    public function deleteUser($id)
    {
        try {
            $user = Customers::findOrFail($id);
            $user->delete();

            return response()->json(["msg" => "delete users success"], 204);
        } catch (ModelNotFoundException $exception) {
            return response()->json(["msg" => $exception->getMessage()], 404);
        }
    }
}
