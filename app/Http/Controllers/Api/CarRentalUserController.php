<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "email":"johndoe@example.com",
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
     *              @OA\Property(property="email", type="string", example="johndoe@example.com"),
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
        //$users = $this->users->createusers($request->all());
        $data = [
            'status' => 'success',
            'message' => 'Hello World!'
        ];
        return response()->json($data);
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
     *                          property="email",
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
     *                     "email":"johndoe@example.com",
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
        try {
            $users = $this->users->updateusers($id, $request->all());
            return response()->json($users);
        } catch (ModelNotFoundException $exception) {
            return response()->json(["msg" => $exception->getMessage()], 404);
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
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="title", type="string", example="title"),
     *              @OA\Property(property="content", type="string", example="content"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *         )
     *     )
     * )
     */
    public function modifyUserData($id, Request $request)
    {
        $users = $this->users->getusers($id);
        if ($users) {
            return response()->json($users);
        }
        return response()->json(["msg" => "users item not found"], 404);
    }

    /**
     * Get user data
     * @OA\Get  (
     *     path="/car-rental/api/v1/users/{id}",
     *     tags={"users"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="title",
     *                         type="string",
     *                         example="example title"
     *                     ),
     *                     @OA\Property(
     *                         property="content",
     *                         type="string",
     *                         example="example content"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
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
    public function getUserData($id)
    {
        $userss = $this->users->getsusers();
        return response()->json(["rows" => $userss]);
    }

    /**
     * Get all users
     * @OA\Get  (
     *     path="/car-rental/api/v1/users",
     *     tags={"users"},
     *      @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="title",
     *                         type="string",
     *                         example="example title"
     *                     ),
     *                     @OA\Property(
     *                         property="content",
     *                         type="string",
     *                         example="example content"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
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
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
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
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="delete users success")
     *         )
     *     )
     * )
     */
    public function deleteUser($id)
    {
        try {
            $users = $this->users->deleteusers($id);
            return response()->json(["msg" => "delete users success"]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(["msg" => $exception->getMessage()], 404);
        }
    }
}
