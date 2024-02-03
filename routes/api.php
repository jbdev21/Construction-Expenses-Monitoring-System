<?php

use App\Http\Controllers\API\AccomplishmentAchievementController;
use App\Http\Controllers\API\AccomplishmentItemController;
use App\Http\Controllers\API\DeliveryController;
use App\Http\Controllers\API\EnterSystemDataEntryControler;
use App\Http\Controllers\API\EquipmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\MaterialController;
use App\Http\Controllers\API\ExpensesGraphController;
use App\Http\Controllers\API\PersonnelController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\WarehouseController;


Route::post('update-role-permission', [RoleController::class, 'updatePermission']);
Route::get('material/select2', [MaterialController::class, 'select2']);
Route::get('expenses/graph-data', [ExpensesGraphController::class, 'expenses']);

Route::get('material/stock-in-warehouse', [MaterialController::class, 'stockInWarehouse']);
Route::get('material/search', [MaterialController::class, 'search']);
Route::get('equipment/search', [EquipmentController::class, 'search']);
Route::get('warehouse/search', [WarehouseController::class, 'search']);
Route::get('warehouse/select2', [WarehouseController::class, 'select2']);

Route::get('personnel/select2', [PersonnelController::class, 'select2']);

Route::get('accomplishment-item', [AccomplishmentItemController::class, 'index']);
Route::post('accomplishment-achievement/get-achievement', [AccomplishmentAchievementController::class, 'index']);
Route::post('accomplishment-achievement/update', [AccomplishmentAchievementController::class, 'update']);
Route::get('project/select2', [ProjectController::class, 'select2']);
Route::get('project/{id}', [ProjectController::class, 'show']);
Route::get('project/{id}/achievement', [ProjectController::class, 'projectAchievement']);
Route::post('project/{project}/weight-progress', [ProjectController::class, 'updateWeightProgress']);
Route::get('project/select-data', [ProjectController::class, 'selectData']);
Route::get('warehouse/select-data', [WarehouseController::class, 'selectData']);
Route::get('delivery/type-list', [DeliveryController::class, 'typeList']);


// Data Entry
Route::post("/data/store", [EnterSystemDataEntryControler::class, 'store']);
Route::post("/data/upload-json", [EnterSystemDataEntryControler::class, 'uploadJSON']);
Route::post("/data/lastid", [EnterSystemDataEntryControler::class, 'checkLastId']);
