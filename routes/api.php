<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ContactController;

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

Route::group(
    ['middleware' => 'api'],
    function ($router) {
        $router->post('/register', [UserController::class, 'register']);
        $router->post('/login', [UserController::class, 'login']);
        $router->post('/logout', [UserController::class, 'logout']);
        $router->post('/update', [UserController::class, 'update']);
        $router->get('/refresh', [UserController::class, 'refresh']);
        $router->get('/profile', [UserController::class, 'profile']);
    }
);

Route::group(
    ['middleware' => 'checkLogin'],
    function () {

        Route::prefix('appointments')->group(function ($router) {
            $router->get('/all', [AppointmentController::class, 'allAppointments']);
            $router->post('/create', [AppointmentController::class, 'createAppointment']);
            $router->get('/delete/{id}', [AppointmentController::class, 'deleteAppointment']);
            $router->get('/', [AppointmentController::class, 'showAppointment']);
        });

        Route::prefix('contacts')->group(function ($router) {
            $router->get('/all', [ContactController::class, 'allContacts']);
            $router->post('/add', [ContactController::class, 'addContact']);
            $router->post('/update/{id}', [ContactController::class, 'updateContact']);
            $router->get('/{id}', [ContactController::class, 'showContact']);
            $router->get('/delete/{id}', [ContactController::class, 'deleteContact']);
        });
    }
);