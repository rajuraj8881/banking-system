<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


// Custom Routes for Transactions
Route::middleware(['auth'])->group(function () {
    Route::controller(TransactionController::class)->group(function () {
        Route::get('/logout', 'destroy')->name('logout');
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/deposit', 'showDeposits')->name('deposit');
        Route::post('/deposit', 'deposit')->name('deposit.store');
        Route::get('/withdrawal', 'showWithdrawals')->name('withdrawal');
        Route::post('/withdrawal', 'withdraw')->name('withdrawal.store');
    });
});
