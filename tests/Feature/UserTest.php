<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_index()
    {
        User::factory(10)->create();

        $response = $this->get('/api/users');

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->count(10)
                ->each(
                    fn ($json) => $json->whereAllType([
                        'id' => 'integer',
                        'name' => 'string',
                        'email' => 'string'
                    ])
                )
        );
    }

    public function test_users_show()
    {
        $user = User::factory()->create();

        $response = $this->get("/api/users/$user->id");

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->whereAllType([
                'id' => 'integer',
                'name' => 'string',
                'email' => 'string'
            ])
                ->where('id', $user->id)
                ->where('name', $user->name)
                ->where('email', $user->email)
        );
    }

    public function test_users_store()
    {
        $data = [
            'name' => 'First User',
            'email' => 'Test@test.test'
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->whereAllType([
                'id' => 'integer',
                'name' => 'string',
                'email' => 'string'
            ])
                ->where('name', $data['name'])
                ->where('email', strtolower($data['email']))
        );

        $this->assertDatabaseHas('users', [
            'email' => strtolower($data['email']),
        ]);

        //attempt creating existing user
        $secondUserData = [
            'name' => 'Second User',
            'email' => 'Test@test.test'
        ];

        $response = $this->postJson('/api/users', $secondUserData);

        $response->assertStatus(422);
    }

    public function test_users_update()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Test',
            'email' => 'Test@test.test'
        ];

        $response = $this->putJson("/api/users/$user->id", $data);

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->whereAllType([
                'id' => 'integer',
                'name' => 'string',
                'email' => 'string'
            ])
                ->where('name', $data['name'])
                ->where('email', strtolower($data['email']))
        );

        $this->assertDatabaseHas('users', [
            'email' => strtolower($data['email']),
        ]);

        //attempt updting with email of existing user
        $secondUserData = [
            'name' => 'Second User',
            'email' => 'Test@test2.test'
        ];

        User::create($secondUserData);

        $response = $this->putJson("/api/users/$user->id", $secondUserData);

        $response->assertStatus(422);
    }

    public function test_users_destroy()
    {
        $user = User::factory()->create();

        $response = $this->delete("/api/users/$user->id");

        $response->assertStatus(200);

        $this->assertModelMissing($user);
    }
}
