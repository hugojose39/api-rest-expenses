<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\TokenController;
use Illuminate\Routing\Router;

/* @var $router Router */

$router->middleware('auth:api')->group(function (Router $router) {
    $router->group(['prefix' => 'expenses'], function (Router $router) {
        $router->get('/', [ExpenseController::class, 'index'])->name('api.expenses.index');
        $router->post('/', [ExpenseController::class, 'store'])->name('api.expenses.store');

        $router->group(['prefix' => '{expense}'], function (Router $router) {
            $router->get('/', [ExpenseController::class, 'show'])->name('api.expenses.show');
            $router->put('/', [ExpenseController::class, 'update'])->name('api.expenses.update');
            $router->delete('/', [ExpenseController::class, 'delete'])->name('api.expenses.delete');
        });
    });
});

$router->middleware(['throttle:5,1'])->group(function (Router $router) {
    $router->post('/token', TokenController::class)->name('api.token');
});
