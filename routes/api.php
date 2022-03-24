<?php

use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'],function(){
    Route::get('/products', function(){
        $products = Product::all();
        return ProductResource::collection($products); 
    });
    
    Route::get('/products/{product}', function(Product $product){
        return new ProductResource($product); 
    });
});



Route::post('/login', function(Request $request){
    $data = $request->only('email', 'password');
    $token = Auth::guard('api')->attempt($data);

    if (!$token){
        return response()->json([
            'error' => 'Invalid Credentials!'
        ], 400);
    }

    return ['token' => $token];
});