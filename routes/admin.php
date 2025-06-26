<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AttendanceController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\BrandmodelController;
use App\Http\Controllers\admin\ColorController;
use App\Http\Controllers\admin\ContractController;
use App\Http\Controllers\admin\EmployeeController;
use App\Http\Controllers\admin\EmployeetypeController;
use App\Http\Controllers\admin\RouteController;
use App\Http\Controllers\admin\RoutezoneController;
use App\Http\Controllers\admin\ScheduleController;
use App\Http\Controllers\admin\ShiftController;
use App\Http\Controllers\admin\VacationController;
use App\Http\Controllers\admin\VehicleController;
use App\Http\Controllers\admin\VehicleimageController;
use App\Http\Controllers\admin\VehicletypeController;
use App\Http\Controllers\admin\ZoneController;
use App\Http\Controllers\admin\ZonecoordController;
use Illuminate\Support\Facades\Route;

Route::resource('/', AdminController::class)->names('admin');


Route::resource('/brands', BrandController::class)->names('admin.brands');
Route::resource('/brandmodels', BrandmodelController::class)->names('admin.models');
Route::resource('/colors', ColorController::class)->names('admin.colors');
Route::get('modelsbybrand/{id}', [BrandmodelController::class, 'modelsbybrand'])->name('admin.modelsbybrand');

Route::resource('/vehiclestypes', VehicletypeController::class)->names('admin.vehtypes');
Route::resource('/vehicles', VehicleController::class)->names('admin.vehicles');
Route::get('vehicles/{vehicle}/images', [VehicleimageController::class, 'show'])->name('admin.vehicles.images.show');
Route::delete('vehicles/images/{image}', [VehicleimageController::class, 'destroy'])->name('admin.vehicles.images.destroy');
Route::post('vehicles/images/{image}/set-profile', [VehicleimageController::class, 'setProfile'])->name('admin.vehicles.images.setprofile');

Route::get('employees/search', [EmployeeController::class, 'search'])->name('admin.employees.search');
Route::get('employees/search-vacation', [EmployeeController::class, 'searchVacation'])->name('admin.employees.searchVacation');
Route::get('employees/available', [EmployeeController::class, 'available'])->name('admin.employees.available');
Route::resource('/employeetypes', EmployeetypeController::class)->names('admin.emptypes');
Route::resource('/employees', EmployeeController::class)->names('admin.employees');

Route::resource('/contracts', ContractController::class)->names('admin.contracts');

Route::get('vacations/search', [VacationController::class, 'search'])->name('admin.vacations.search');
Route::get('vacations/check', [VacationController::class, 'check'])->name('admin.vacations.check');
Route::resource('/vacations', VacationController::class)->names('admin.vacations');

Route::get('/attendances', [AttendanceController::class, 'index'])->name('admin.attendances.index');
Route::get('attendances/filter', [AttendanceController::class, 'filter'])->name('admin.attendances.filter');

Route::resource('/zones', ZoneController::class)->names('admin.zones');
Route::resource('/zonecoords', ZonecoordController::class)->names('admin.zonecoords');

Route::resource('/routes', RouteController::class)->names('admin.routes');
Route::resource('/routezones', RoutezoneController::class)->names('admin.routezones');
Route::get('routezones/create/{route_id}', [RoutezoneController::class, 'create'])->name('admin.routezones.create');

Route::resource('/shifts', ShiftController::class)->names('admin.shifts');

Route::resource('/schedules', ScheduleController::class)->names('admin.schedules');


