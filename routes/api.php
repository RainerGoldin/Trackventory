<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\BorrowStatusController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PurchaseInvoiceController;
use App\Http\Controllers\Api\RequestStatusController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\PurchaseRequestController;
use App\Http\Controllers\Api\ItemBorrowedController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('borrow-statuses', BorrowStatusController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('purchase-invoices', PurchaseInvoiceController::class);
    Route::apiResource('request-statuses', RequestStatusController::class);
    Route::apiResource('items', ItemController::class);
    Route::apiResource('purchase-requests', PurchaseRequestController::class);
    Route::apiResource('item-borroweds', ItemBorrowedController::class);
});