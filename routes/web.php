<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Boutique (côté visiteur)
|--------------------------------------------------------------------------
*/

Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/produit/{slug}', [ShopController::class, 'show'])->name('product.show');

Route::prefix('panier')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/ajouter', [CartController::class, 'add'])->name('add');
    Route::post('/modifier', [CartController::class, 'update'])->name('update');
    Route::post('/supprimer', [CartController::class, 'remove'])->name('remove');
    Route::post('/vider', [CartController::class, 'clear'])->name('clear');
});

Route::get('/commande', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('/commande', [OrderController::class, 'store'])->name('order.store');
Route::get('/commande/succes/{orderNumber}', [OrderController::class, 'success'])->name('order.success');

/*
|--------------------------------------------------------------------------
| Administration (côté boss)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware([AdminAuth::class])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('profil')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::put('/mot-de-passe', [ProfileController::class, 'changePassword'])->name('password');
        });

        Route::prefix('produits')->name('products.')->group(function () {
            Route::get('/', [AdminProductController::class, 'index'])->name('index');
            Route::get('/creer', [AdminProductController::class, 'create'])->name('create');
            Route::post('/creer', [AdminProductController::class, 'store'])->name('store');
            Route::get('/{product}/modifier', [AdminProductController::class, 'edit'])->name('edit');
            Route::put('/{product}/modifier', [AdminProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('destroy');
            Route::post('/{product}/toggle', [AdminProductController::class, 'toggle'])->name('toggle');
            Route::delete('/image/{image}', [AdminProductController::class, 'destroyImage'])->name('destroyImage');
            Route::post('/{product}/image/{image}/principale', [AdminProductController::class, 'setMainImage'])->name('setMainImage');
            Route::post('/{product}/image/{image}/survol', [AdminProductController::class, 'setHoverImage'])->name('setHoverImage');
        });

        Route::prefix('commandes')->name('orders.')->group(function () {
            Route::get('/', [AdminOrderController::class, 'index'])->name('index');
            Route::get('/export', [AdminOrderController::class, 'export'])->name('export');
            Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
            Route::post('/{order}/statut', [AdminOrderController::class, 'updateStatus'])->name('updateStatus');
            Route::delete('/{order}', [AdminOrderController::class, 'destroy'])->name('destroy');
        });
    });
});
