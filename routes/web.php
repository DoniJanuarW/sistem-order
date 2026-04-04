<?php

use Illuminate\Support\Facades\Route;



Route::middleware('role:customer,cashier,admin')->group(function () {
    Route::post('/midtrans-callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
});

Route::middleware('role:guest')->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'home'])->name('home');
    Route::controller(\App\Http\Controllers\AuthController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::get('/register', 'register')->name('register');
        Route::post('/login', 'authenticate')->name('login.store');
        Route::post('/register', 'store')->name('register.store');
    });
});



Route::prefix('admin')->middleware('role:admin')->group(function () {
    Route::prefix('dashboard')->controller(\App\Http\Controllers\DashboardController::class)->group(function () {
        Route::get('/export', 'exportMaster')->name('admin.dashboard.export');
    });
    
    Route::prefix('user')->controller(\App\Http\Controllers\Admin\UserController::class)->group(function () {
        Route::get('/table/user', 'tableUser')->name('admin.user.tableUser');
        Route::get('/', 'index')->name('admin.user.index');
        Route::get('/create', 'create')->name('admin.user.create');
        Route::get('/edit/{id}', 'edit')->name('admin.user.edit');
        Route::get('/{id}', 'get')->name('admin.user.get'); 

        Route::post('/', 'store')->name('admin.user.store'); 
        Route::put('/{id}', 'update')->name('admin.user.update');
        Route::delete('/{id}', 'destroy')->name('admin.user.destroy');
    });

    Route::prefix('table')->controller(\App\Http\Controllers\Admin\TableController::class)->group(function () {
        Route::get('/table/table', 'tableTable')->name('admin.table.tableTable');
        Route::get('/', 'index')->name('admin.table.index');
        Route::get('/create', 'create')->name('admin.table.create');
        Route::get('/edit/{id}', 'edit')->name('admin.table.edit');
        Route::get('/{id}', 'get')->name('admin.table.get')->whereNumber('id'); 
        Route::get('/qr/{id}', 'qr')->name('admin.table.qr')->whereNumber('id'); 
        Route::get('/generate-pdf', 'generateAllQrPdf')->name('admin.table.pdf');

        Route::post('/', 'store')->name('admin.table.store'); 
        Route::put('/{id}', 'update')->name('admin.table.update');
        Route::delete('/{id}', 'destroy')->name('admin.table.destroy');

        Route::post('/{id}/toggle-status', 'toggleStatus');
    });

    Route::prefix('menu')->controller(\App\Http\Controllers\Admin\MenuController::class)->group(function () {
        Route::get('/menu/menu', 'tableMenu')->name('admin.menu.tableMenu');
        Route::get('/', 'index')->name('admin.menu.index');
        Route::get('/create', 'create')->name('admin.menu.create');
        Route::get('/edit/{id}', 'edit')->name('admin.menu.edit');
        Route::get('/{id}', 'get')->name('admin.menu.get'); 

        Route::post('/', 'store')->name('admin.menu.store'); 
        Route::put('/{id}', 'update')->name('admin.menu.update');
        Route::delete('/{id}', 'destroy')->name('admin.menu.destroy');

        Route::post('/{id}/toggle-status', 'toggleStatus');
    });

    Route::prefix('category')->controller(\App\Http\Controllers\Admin\CategoryController::class)->group(function () {
        Route::get('/category/category', 'tableCategory')->name('admin.category.tableCategory');
        Route::get('/', 'index')->name('admin.category.index');
        Route::get('/create', 'create')->name('admin.category.create');
        Route::get('/edit/{id}', 'edit')->name('admin.category.edit');
        Route::get('/{id}', 'get')->name('admin.category.get'); 

        Route::post('/', 'store')->name('admin.category.store'); 
        Route::put('/{id}', 'update')->name('admin.category.update');
        Route::delete('/{id}', 'destroy')->name('admin.category.destroy');
    });

    Route::prefix('order')->controller(\App\Http\Controllers\Admin\OrderController::class)->group(function () {
        Route::get('/', 'index')->name('admin.order.index');
        Route::get('/export', 'export')->name('admin.order.export');
    });

});


Route::prefix('cashier')->middleware('role:cashier')->group(function () {
    Route::prefix('table')->controller(\App\Http\Controllers\Cashier\TableController::class)->group(function () {
        route::get('/get', 'all')->name('cashier.table.all');
    });

    Route::prefix('menu')->controller(\App\Http\Controllers\Cashier\MenuController::class)->group(function () {
        route::get('/all', 'all')->name('cashier.menu.all');
        Route::get('/menu/menu', 'tableMenu')->name('cashier.menu.tableMenu');
        Route::get('/', 'index')->name('cashier.menu.index');
        Route::get('/{id}', 'get')->name('cashier.menu.get')->whereNumber('id');; 

        Route::post('/{id}/toggle-status', 'toggleStatus');
    });

    Route::prefix('order')->controller(\App\Http\Controllers\Cashier\OrderController::class)->group(function () {
        Route::get('/', 'index')->name('cashier.order.index');
        Route::get('/manual', 'manual')->name('cashier.order.create');
        Route::get('/all', 'all')->name('cashier.order.all');
        Route::get('/detail/{id}', 'detail')->name('cashier.order.detail');
        Route::get('/{id}', 'show')->name('cashier.order.show');
        Route::get('/{id}/pdf', 'downloadPdf')->name('cashier.order.pdf');

        Route::post('/', 'store')->name('cashier.order.store');
        Route::patch('/{id}', 'updateStatus')->name('cashier.order.update-status');        

    });
    
    Route::prefix('transaction')->controller(\App\Http\Controllers\Cashier\TransactionController::class)->group(function () {
        Route::get('/', 'index')->name('cashier.transaction.index');   
    });
    Route::prefix('payment')->controller(\App\Http\Controllers\Cashier\PaymentController::class)->group(function () {
        Route::get('/', 'index')->name('cashier.payment.all');  
        Route::get('/this-month', 'thisMonth')->name('cashier.payment.thisMonth');   
        Route::get('/filter', 'filter')->name('cashier.payment.filter');   

        Route::patch('/{id}', 'updateStatus')->name('cashier.payment.update-payment');   
    });

});

Route::middleware('role:customer')->group(function () {
    Route::prefix('cart')->controller(\App\Http\Controllers\Customer\CartController::class)->group(function () {
        Route::get('/', 'index')->name('customer.cart.index');  
        
        Route::post('/add', 'addToCart')->name('customer.cart.addToCart');
        Route::post('/update-qty/{id}', 'updateQty')->name('customer.cart.updateQty')->whereNumber('id');
        Route::post('/update-note/{id}', 'updateNote')->name('customer.cart.updateNote')->whereNumber('id');

        Route::delete('/{id}', 'destroy')->name('customer.cart.destroy')->whereNumber('id');
        Route::delete('/clear', 'clearCart')->name('customer.cart.clearCart');
    });

    Route::prefix('order')->controller(\App\Http\Controllers\Customer\OrderController::class)->group(function () {
        Route::post('/checkout', 'checkout')->name('customer.order.checkout');
        Route::get('/history', 'history')->name('customer.order.history');
        Route::get('/{id}/pdf', 'downloadPdf')->name('customer.order.pdf');
    });

    Route::prefix('payment')->controller(\App\Http\Controllers\Customer\PaymentController::class)->group(function () {
        Route::get('/{orderId}', 'paymentPage')->name('customer.payment.paymentPage');
        Route::get('/{orderId}/cancel', 'cancelOrder')->name('customer.payment.cancelOrder');
        Route::post('/{orderId}/confirm', 'confirmPayment')->name('customer.payment.confirm');
    });

    Route::prefix('scan')->controller(\App\Http\Controllers\Customer\ScanController::class)->group(function () {
        Route::get('/', 'scanPage')->name('customer.scan.scanPage');
        Route::post('/validate', 'validateTable')->name('customer.scan.validate');
    });
    
    Route::get('/profile', function () {
        return view('customer.profile');
    })->name('customer.profile');
});