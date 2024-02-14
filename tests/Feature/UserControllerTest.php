<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    function setUp(): void
    {
        parent::setUp();

        User::factory(13)->create();
        User::factory()->create([
            'name' => 'test_user_1',
            'email' => fake()->unique()->safeEmail(),
            'age' => fake()->numberBetween(18, 50),
        ]);
        User::factory()->create([
            'name' => 'test_user_2',
            'email' => fake()->unique()->safeEmail(),
            'age' => fake()->numberBetween(18, 50),
        ]);

        $this->apiUrl = '/api/v1/users/';
    }

    public function testGetUsersValidRequest(): void
    {
        $data = [
            'page' => 2,
            'per_page' => '1',
            'search' => '',
        ];

        $response = $this->post($this->apiUrl, $data)
            ->assertStatus(200);

        $userId = $response->json()['items'][0]['id'];
        $this->assertEquals(2, $userId);
    }

    public function testGetUsersValidRequestWithSearch(): void
    {
        $data = [
            'page' => 2,
            'per_page' => '1',
            'search' => 'test_user',
        ];

        $response = $this->post($this->apiUrl, $data)
            ->assertStatus(200);

        $userId = $response->json()['items'][0]['name'];
        $this->assertEquals('test_user_2', $userId);
    }

    public function testGetUsersNotValidRequest(): void
    {
        $data = [
            'page' => 'test',
            'per_page' => '1',
            'search' => '',
        ];

        $this->post($this->apiUrl, $data)
            ->assertStatus(400)
            ->assertJson(['page' =>
                [
                    'The page field must be an integer.',
                ]
            ]);
    }


    public function testHandleDeleteUserFound(): void
    {
        $userID = User::query()->first()->value('id');
        $this->assertDatabaseHas('users', ['id' => $userID]);

        $this->delete($this->apiUrl . $userID)
            ->assertStatus(200);

        $this->assertDatabaseMissing('users', ['id' => $userID]);
    }

    public function testHandleDeleteUserNotFound(): void
    {
        $this->assertDatabaseMissing('users', ['id' => 404]);

        $this->delete($this->apiUrl . 404)
            ->assertStatus(404);
    }

    public function testUpdateUserValidRequest(): void
    {
        $user = User::query()->first();
        $userID = User::query()->first()->value('id');

        $actualData = [
            'name' => $user->name,
            'email' => $user->email,
            'age' => $user->age,
        ];
        $expectedData = [
            'name' => 'test_update_name',
            'email' => 'test_update_email@mail.ru',
            'age' => 200,
        ];
        $this->assertNotEquals($expectedData, $actualData);

        $response = $this->patch($this->apiUrl . $userID, $expectedData);
        $response->assertStatus(200);

        $userWithUpdatedData = User::query()->findOrFail($userID);
        $updatedUserData = [
            'name' => $userWithUpdatedData->name,
            'email' => $userWithUpdatedData->email,
            'age' => $userWithUpdatedData->age,
        ];

        $this->assertEquals($expectedData, $updatedUserData);
    }

    public function testUpdateUserNotValidRequest(): void
    {
        $user = User::query()->first();
        $userID = User::query()->first()->value('id');

        $actualData = [
            'name' => $user->name,
            'email' => $user->email,
            'age' => $user->age,
        ];
        $expectedData = [
            'name' => 'test_update_name',
            'email' => 'test_update_email',
            'age' => 200,
        ];
        $this->assertNotEquals($expectedData, $actualData);

        $this->patch($this->apiUrl . $userID, $expectedData)
            ->assertStatus(400)
            ->assertJson(['email' =>
                [
                    'The email field must be a valid email address.',
                ]
            ]);

        $userWithUpdatedData = User::query()->findOrFail($userID);
        $updatedUserData = [
            'name' => $userWithUpdatedData->name,
            'email' => $userWithUpdatedData->email,
            'age' => $userWithUpdatedData->age,
        ];

        $this->assertNotEquals($expectedData, $updatedUserData);
    }

    public function testCreateUserValidRequest(): void
    {
        $data = [
            'name' => 'new_created_name',
            'email' => 'new_created_email@mail.ru',
            'age' => 200,
        ];

        $this->assertDatabaseMissing('users', $data);

        $response = $this->post($this->apiUrl . 'create', $data);
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', $data);
    }

    public function testCreateUserNotValidRequest(): void
    {
        $data = [
            'name' => 'new_created_name',
            'email' => 'new_created_email',
            'age' => 200,
        ];

        $response = $this->post($this->apiUrl . 'create', $data);
        $response->assertStatus(400);

        $this->assertDatabaseMissing('users', $data);
    }
}
