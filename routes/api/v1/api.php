<?php

use App\Http\Controllers\api\v1\AccountTypeController;
use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\DeleteController;
use App\Http\Controllers\api\v1\ItemCategoryController;
use App\Http\Controllers\api\v1\ItemController;
use App\Http\Controllers\api\v1\ItemSubCategoryController;
use App\Http\Controllers\api\v1\ResourceController;
use App\Http\Controllers\api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\ApproveAuthorizationController;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController;
use Laravel\Passport\Http\Controllers\ClientController;
use Laravel\Passport\Http\Controllers\DenyAuthorizationController;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;
use Laravel\Passport\Http\Controllers\ScopeController;
use Laravel\Passport\Http\Controllers\TransientTokenController;

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

Route::get('/test-api', function (Request $request) {
    return ("API is working fine.");
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);

//Users CRUD Routes
Route::middleware('auth:api')->apiResource('users', UserController::class);
Route::middleware('auth:api')->apiResource('account-types', AccountTypeController::class);
Route::apiResource('item-categories', ItemCategoryController::class);
Route::apiResource('item-sub-categories', ItemSubCategoryController::class);
Route::apiResource('items', ItemController::class);
Route::post('filter-items', [ItemController::class, 'filterItems']);

//Laravel Passport Routes
Route::middleware('auth:api')->get('oauth/authorize', [AuthorizationController::class, 'authorize']);
Route::middleware('auth:api')->post('oauth/authorize', [ApproveAuthorizationController::class, 'approve']);
Route::middleware('auth:api')->delete('oauth/authorize', [DenyAuthorizationController::class, 'deny']);

Route::middleware('auth:api')->get('oauth/clients', [ClientController::class, 'forUser']);
Route::middleware('auth:api')->post('oauth/clients', [ClientController::class, 'store']);
Route::middleware('auth:api')->delete('oauth/clients/{client_id}', [ClientController::class, 'destroy']);
Route::middleware('auth:api')->put('oauth/clients/{client_id}', [ClientController::class, 'update']);


Route::middleware('auth:api')->get('oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'forUser']);
Route::middleware('auth:api')->post('oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'store']);
Route::middleware('auth:api')->delete('oauth/personal-access-tokens/{token_id}', [PersonalAccessTokenController::class, 'destroy']);

Route::middleware('auth:api')->get('oauth/scopes', [ScopeController::class, 'all']);

Route::middleware('auth:api')->post('oauth/token', [AccessTokenController::class, 'issueToken']);
Route::middleware('auth:api')->post('oauth/token/refresh', [TransientTokenController::class, 'refresh']);
Route::middleware('auth:api')->get('oauth/tokens', [AuthorizedAccessTokenController::class, 'forUser']);
Route::middleware('auth:api')->delete('oauth/tokens/{token_id}', [AuthorizedAccessTokenController::class, 'destroy']);

//Sync Resources
Route::middleware('auth:api')->get('sync-resources', [ResourceController::class, 'syncResources']);

Route::get('get-item-categories', [ResourceController::class, 'getItemCategories']);

//Route::get('/authorize', function () {
//    return view('vendor.passport.authorize');
//});
