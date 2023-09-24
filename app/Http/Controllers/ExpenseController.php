<?php

namespace App\Http\Controllers;

use App\Http\Queries\ExpenseQuery;
use App\Http\Requests\Expense\CreateRequest;
use App\Http\Requests\Expense\UpdateRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController
{
    use AuthorizesRequests;

    public function __construct(private readonly Factory $auth, private readonly Expense $expense)
    {
        //
    }

    public function index(ExpenseQuery $query, Request $request): JsonResource
    {
        $this->authorize('viewAny', Expense::class);

        $expenses = $query
            ->where('user_id', $this->auth->user()->id)
            ->simplePaginate($request->get('limit', config('app.pagination_limit')))
            ->appends($request->query());

        return ExpenseResource::collection($expenses);
    }

    public function show(Expense $expense, ExpenseQuery $query): JsonResource
    {
        $this->authorize('view', $expense);

        $expense = $query
            ->where('id', $expense->id)
            ->firstOrFail();

        return new ExpenseResource($expense);
    }

    public function store(CreateRequest $request): JsonResource
    {
        $this->authorize('create', Expense::class);

        $expense = $this->expense->create($request->validated());

        return new ExpenseResource($expense);
    }

    public function update(Expense $expense, UpdateRequest $request): JsonResource
    {
        $this->authorize('update', $expense);

        $expense->update($request->validated());

        return new ExpenseResource($expense);
    }

    public function delete(Expense $expense): JsonResponse
    {
        $this->authorize('delete', $expense);

        $expense->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
