<?php

namespace Tests\Feature;

use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    public function test_cars_index()
    {
        Car::factory(10)->create();

        $response = $this->getJson('/api/cars');

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->count(10)
                ->each(
                    fn ($json) => $json->whereAllType([
                        'id' => 'integer',
                        'model' => 'string'
                    ])
                )
        );
    }

    public function test_cars_show()
    {
        $car = Car::factory()->create();

        $response = $this->getJson("/api/cars/$car->id");

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->whereAllType([
                'id' => 'integer',
                'model' => 'string'
            ])
                ->where('id', $car->id)
                ->where('model', $car->model)
        );
    }

    public function test_cars_store()
    {
        $model = 'BMW x5';
        $response = $this->postJson('/api/cars', ['model' => $model]);

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->whereAllType([
                'id' => 'integer',
                'model' => 'string'
            ])
                ->where('model',  $model)
        );

        $this->assertDatabaseHas('cars', [
            'model' => $model,
        ]);
    }

    public function test_cars_update()
    {
        $car = Car::factory()->create();

        $data = [
            'model' => 'Test updating car model'
        ];

        $response = $this->putJson("/api/cars/$car->id", $data);

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->whereAllType([
                'id' => 'integer',
                'model' => 'string'
            ])
                ->where('model', $data['model'])
        );

        $this->assertDatabaseHas('cars', [
            'model' => $data['model'],
        ]);
    }

    public function test_cars_destroy()
    {
        $car = Car::factory()->create();

        $response = $this->delete("/api/cars/$car->id");

        $response->assertStatus(200);

        $this->assertModelMissing($car);
    }
}
