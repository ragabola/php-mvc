<?php

use App\Http\Controllers\HomeController;

$router->get('/users', [HomeController::class, 'index']);
$router->get('/users/create', [HomeController::class, 'create']);
$router->post('/users', [HomeController::class, 'store']);
$router->get('/users/{username}', [HomeController::class, 'show']);
$router->get('/users/{username}/edit', [HomeController::class, 'edit']);
$router->patch('/users/{username}', [HomeController::class, 'update']);
$router->delete('/users/{username}', [HomeController::class, 'destroy']);




