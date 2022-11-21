<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CarsharingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_bind_car()
    {
        $firstCar = Car::factory()->create();
        $secondCar = Car::factory()->create();
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $response = $this->postJson('/api/bind-car', [
            'car_id' => $firstCar->id,
            'user_id' => $firstUser->id
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('car_user', [
            'car_id' => $firstCar->id,
            'user_id' => $firstUser->id
        ]);

        // user_id, car_id not provided
        $response = $this->postJson('/api/bind-car');
        $response->assertStatus(422);

        // car already binded
        $response = $this->postJson('/api/bind-car', [
            'car_id' => $firstCar->id,
            'user_id' => $secondUser->id
        ]);
        $response->assertStatus(400)->assertJson([
            'error' => 'CAR_IS_NOT_FREE',
        ]);

        // user already has car
        $response = $this->postJson('/api/bind-car', [
            'car_id' => $secondCar->id,
            'user_id' => $firstUser->id
        ]);
        $response->assertStatus(400)->assertJson([
            'error' => 'USER_ALREADY_HAS_CAR',
        ]);
    }

    public function test_unbind_car()
    {
        $car = Car::factory()->create();
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $firstUser->cars()->attach($car);

        $response = $this->postJson('/api/unbind-car', [
            'user_id' => $firstUser->id
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('car_user', [
            'user_id' => $firstUser->id
        ]);

        // user doesnt have car
        $response = $this->postJson('/api/unbind-car', ['user_id' => $secondUser->id]);
        $response->assertStatus(400)->assertJson([
            'error' => 'USER_DOES_NOT_HAVE_CAR',
        ]);
    }

    public function test_list()
    {
        $fistCar = Car::factory()->create();
        $secondCar = Car::factory()->create();
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $firstUser->cars()->attach($fistCar);
        $secondUser->cars()->attach($secondCar);

        $response = $this->getJson('/api/list');
        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->count(2)
                ->each(
                    fn ($json) => $json->whereAllType([
                        'user_id' => 'integer',
                        'car_id' => 'integer'
                    ])
                )
        );
    }
}
