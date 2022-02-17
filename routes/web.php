<?php

use App\Http\Controllers\Painel\CategoryController;
use App\Http\Controllers\Painel\CompanyController;
use App\Http\Controllers\Painel\CustomerController;
use App\Http\Controllers\Painel\ConfigController;
use App\Http\Controllers\Painel\DeliveryController;
use App\Http\Controllers\Painel\HomeController;
use App\Http\Controllers\Painel\InputController;
use App\Http\Controllers\Painel\ProductController;
use App\Http\Controllers\Painel\ProfileController;
use App\Http\Controllers\Painel\ProviderController;
use App\Http\Controllers\Painel\RecipeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

Route::prefix('painel')->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('painel');
    Route::post('email',[HomeController::class,'email'])->name('email');

    Route::resource('inputs', InputController::class); //pronto
    Route::resource('category', CategoryController::class); //pronto
    Route::resource('providers', ProviderController::class);
    Route::resource('products', ProductController::class);
    Route::resource('recipes',RecipeController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('profile', ProfileController::class);
    Route::get('company', [CompanyController::class, 'index'])->name('company'); 
    Route::put('companysave', [CompanyController::class, 'save'])->name('company.save'); 

    //Rotas configuration
    Route::get('config',[ConfigController::class,'index'])->name('config');
    Route::post('configifood',[ConfigController::class,'ifoodsave'])->name('config.ifood.save');

    //Rotas Delivery
    Route::get('delivery',[DeliveryController::class, 'painelDelivery'])->name('delivery');
    Route::get('pooling', [DeliveryController::class,'updateOrder'])->name('pooling');
    Route::get('merchantdata', [DeliveryController::class,'infoMerchant'])->name('merchantdata');
    Route::get('orderdata', [DeliveryController::class,'infoOrder'])->name('orderdata');

});

require __DIR__ . '/auth.php';
