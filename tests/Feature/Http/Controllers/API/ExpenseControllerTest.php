<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class ExpenseControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    private Expense $expense;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->user = User::factory()->create();
        $this->expense = Expense::factory()->for($this->user)->create();
    }

    public function test_index_endpoint_structure(): void
    {
        $response = $this->actingAs($this->user)->get(route('api.expenses.index'));

        $response->assertOk();
        $response->assertJsonStructure($this->expensesIndexItemStructure());
    }

    public function test_show_endpoint_structure(): void
    {
        $response = $this->actingAs($this->user)->get(route('api.expenses.show', ['expense' => $this->expense]));

        $response->assertOk();
        $response->assertJsonStructure($this->expensesShowItemStructure());
    }

    public function test_store_endpoint_structure(): void
    {
        $response = $this->actingAs($this->user)->post(route('api.expenses.store', [
            'description' => Str::random(64),
            'user_id' => $this->user->id ,
            'value' => 10,
            'date' => now()->subMinutes(10)->setTimezone('America/Sao_Paulo')->format('Y-m-d H:i:s')
        ]));

        $response->assertCreated();
        $response->assertJsonStructure($this->expensesShowItemStructure());
    }

    public function test_update_endpoint_structure(): void
    {
        $response = $this->actingAs($this->user)->put(route('api.expenses.update', [
            'expense' => $this->expense,
            'description' => Str::random(64),
            'user_id' => $this->user->id ,
            'value' => 10,
            'date' => now()->subMinutes(10)->setTimezone('America/Sao_Paulo')->format('Y-m-d H:i:s')
        ]));

        $response->assertOk();
        $response->assertJsonStructure($this->expensesShowItemStructure());
    }

    public function test_delete_endpoint_structure(): void
    {
        $this->actingAs($this->user)->delete(route('api.expenses.delete', ['expense' => $this->expense]))->assertNoContent();
    }

    /**
     * @dataProvider expenseDataProvider
     */
    public function test_store_endpoint_validation(string $field, string $message, array $data): void
    {
        $this->expense->forceDelete();

        $response = $this->actingAs($this->user)->post(route('api.expenses.store', [
            ...$data,
        ]));

        $this->assertDatabaseCount('expenses', 0);
        $response->assertInvalid([$field => $message]);
        $this->assertEquals(422, $response->exception->status);
    }

    /**
     * @dataProvider expenseDataProvider
     */
    public function test_update_endpoint_validation(string $field, string $message, array $data): void
    {
        $response = $this->actingAs($this->user)->put(route('api.expenses.update', [
            'expense' => $this->expense,
            ...$data,
        ]));

        $response->assertInvalid([$field => $message]);
        $this->assertEquals(422, $response->exception->status);
    }

    public static function expenseDataProvider(): array
    {
        return [
            [
                'user_id',
                'The user id field is required.',
                [
                    'user_id' => null,
                ],
            ],
            [
                'value',
                'The value field is required.',
                [
                    'value' => null,
                ],
            ],
            [
                'description',
                'The description field is required.',
                [
                    'description' => null,
                ],
            ],
            [
                'date',
                'The date field is required.',
                [
                    'date' => null,
                ],
            ],
            [
                'user_id',
                'The selected user id is invalid.',
                [
                    'user_id' => 1234564897,
                ],
            ],
            [
                'value',
                'The value field must be an integer.',
                [
                    'value' => '10BR',
                ],
            ],
            [
                'description',
                'The description field must not be greater than 191 characters.',
                [
                    'description' => Str::random(200),
                ],
            ],
            [
                'date',
                'The date field must match the format Y-m-d H:i:s.',
                [
                    'date' => now()->subMinutes(10)->setTimezone('America/Sao_Paulo')->format('d/m/Y')
                ],
            ],
            [
                'value',
                'The value field must be at least 0.',
                [
                    'value' => -1,
                ],
            ],
            [
                'date',
                'invalid-date',
                [
                    'date' => now()->addMinutes(10)->setTimezone('America/Sao_Paulo')->format('Y-m-d H:i:s')
                ],
            ],
        ];
    }

    private function expensesIndexItemStructure(): array
    {
        return [
            'data' => [
                '*' => $this->expensesItemStructure(),
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'path',
                'per_page',
                'to',
            ],
        ];
    }


    private function expensesShowItemStructure(): array
    {
        return [
            'data' => $this->expensesItemStructure(),
        ];
    }

    private function expensesItemStructure(): array
    {
        return [
            'created_at',
            'date',
            'description',
            'id',
            'updated_at',
            'user_id',
            'value',
        ];
    }
}
