<?php

namespace Tests\Feature;

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CarRentalCurrencyControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test get currency amount.
     */
    public function testGetCurrencyAmount()
    {
        $response = $this->postJson('/car-rental/api/v1/currencies', [
            'currency' => 'EUR',
            'amount' => 40.5
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'currency_amount',
            ]);
    }

    /**
     * Test get currency list.
     */
    public function testGetCurrencyList()
    {
        $response = $this->getJson('/car-rental/api/v1/currencies');

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'currency_amount',
            ]);
    }
}
