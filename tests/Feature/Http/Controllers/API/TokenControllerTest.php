<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Models\User as ModelsUser;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TokenControllerTest extends TestCase
{
    use LazilyRefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider tokenRequestDataProvider
     */
    public function test_token_request_endpoint_validation(string $field, string $message, array $data): void
    {
        $response = $this->post(route('api.token', $data));

        $response->assertInvalid([$field => $message]);
        $this->assertEquals(422, $response->exception->status);
    }

    public static function tokenRequestDataProvider(): array
    {
        return [
            [
                'email',
                'The email field is required.',
                [
                    'email' => null,
                ],
            ],
            [
                'email',
                'The email field must be a valid email address.',
                [
                    'email' => 123,
                ],
            ],
            [
                'email',
                'The email field must be a valid email address.',
                [
                    'email' => 'a',
                ],
            ],
            [
                'token_name',
                'The token name field is required.',
                [
                    'token_name' => null,
                ],
            ],
        ];
    }

    public function test_if_it_will_return_not_found_even_when_the_user_does_not_exists(): void
    {
        $response = $this->post(route('api.token', [
            'email' => $this->faker->safeEmail(),
            'token_name' => $this->faker->name(),
        ]));

        $response->assertNotFound();
    }

    public function test_if_it_will_create_pin_properly_for_the_user(): void
    {
        $user = ModelsUser::factory()->create(['email' => $this->faker->safeEmail()]);

        $response = $this->post(route('api.token', [
            'email' => $user->email,
            'token_name' => $this->faker->name(),
        ]));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => ['token'],
        ]);
    }
}
