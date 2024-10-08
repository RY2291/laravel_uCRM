<?php

use App\Http\Controllers\api\AnalysisController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Customer;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/analysis', [AnalysisController::class, 'index'])
->name('api.analysis');

Route::middleware('auth:sanctum')->get('/searchCustomer', function (Request $request) {
    return Customer::searchCustomers($request->search)
    ->select('id', 'name', 'kana', 'tel')->paginate(20);
    // return dd($request->search);
});
