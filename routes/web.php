<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Lead\LeadController;
use App\Http\Controllers\Lead\LeadUpdateController;
use App\Http\Controllers\Admin\EmployeeController;



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
    if (Auth::check()) {
        return redirect(route('home'));
    }
    return redirect(route('login'));
});
Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::group(['middleware' => 'guest'], function () {
        Route::get('login', [AuthController::class, 'login'])->name('login');
        Route::post('login', [AuthController::class, 'postLogin'])->name('postLogin');
});

Route::middleware(['auth'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('employee', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/leads', [LeadController::class, 'index'])->name('home');
    Route::post('/leads', [LeadController::class, 'store'])->name('lead.store');
    Route::get('/leads/{id}/edit', [LeadController::class, 'edit'])->name('lead.edit');
    Route::put('/leads/{id}', [LeadController::class, 'update']);
    Route::get('/lead/updates', [LeadUpdateController::class, 'index'])->name('leads.update');
    Route::post('/lead/update', [LeadUpdateController::class, 'store'])->name('leads.update.store');
});
