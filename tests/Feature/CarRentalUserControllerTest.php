<?php

namespace Tests\Feature;

use App\Models\Customers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash as HashFacade;
use Illuminate\Contracts\Hashing\Hasher as Hash;
use Tests\TestCase;

class CarRentalUserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test user login
     */
    public function testUserLogin()
    {
        $user = Customers::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe123',
            'password' =>  HashFacade::make('password123'),
        ]);

        $response = $this->postJson('/car-rental/api/v1/users/login', [
            'username' => 'johndoe123',
            'password' => 'password123',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'firstname',
                'lastname',
                'username',
                'token',
            ]);
    }

    /**
     * Test user registration
     */
    public function testUserRegistration()
    {
        $response = $this->postJson('/car-rental/api/v1/users/register', [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'username' => 'johndoe123',
            'password' => 'password123',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'msg' => 'Registration successful',
            ]);
    }

    /**
     * Test user data retrieval
     */
    public function testUserDataRetrieval()
    {
        $user = Customers::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe123',
            'password' =>  HashFacade::make('password123'),
        ]);

        $response = $this->getJson("/car-rental/api/v1/users/{$user->customer_id}");

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'firstname',
                'lastname',
                'username',
                'created_at',
            ]);
    }
}
