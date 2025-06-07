<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\SiteController;

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

// Auth
Route::get('login', [AuthController::class, 'getLogin'])->middleware('guest')->name('login');
Route::post('login', [AuthController::class, 'postLogin'])->middleware('guest')->name('login');
Route::get('cadastro', [AuthController::class, 'getRegister'])->middleware('guest')->name('register');
Route::post('cadastro', [AuthController::class, 'postRegister'])->middleware('guest')->name('register');
Route::post('logout', [AuthController::class, 'getLogout'])->name('logout');

// Veterinário
Route::get('vet', [SiteController::class, 'getVet'])->middleware('auth:vet')->name('vet');
Route::get('editar-consulta/{appointment_id}', [SiteController::class, 'getEditAppointment'])->name('vet.edit-appointment');

// Cliente
Route::get('cliente', [SiteController::class, 'getClient'])->middleware('auth')->name('client');
Route::get('editar-paciente/{patient_id?}', [SiteController::class, 'getEditPatient'])->name('client.edit-patient');
Route::post('editar-paciente/{patient_id?}', [SiteController::class, 'postEditPatient'])->name('client.edit-patient');
Route::get('remover-paciente/{patient_id}', [SiteController::class, 'getRemovePatient'])->name('client.remove-patient');
Route::get('agendar-consulta', [SiteController::class, 'getCreateAppointment'])->name('client.create-appointment');
Route::post('agendar-consulta', [SiteController::class, 'postCreateAppointment'])->name('client.create-appointment');
Route::get('consulta/{appointment_id}', [SiteController::class, 'getAppointment'])->name('client.view-appointment');

// Website
Route::get('', [SiteController::class, 'getIndex'])->name('index');
