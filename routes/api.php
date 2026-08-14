<?php

use App\Http\Controllers\Api\ProductController as ApiProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ApiProductController::class);
