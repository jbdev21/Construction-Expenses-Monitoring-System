<?php

use App\Http\Controllers\AccomplishmentItemController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DarkModeController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MotorpoolController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\PettyCashController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\CashAdvanceController;
use App\Http\Controllers\CashInController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\MonthlyAchievementController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProjectActivityController;
use App\Http\Controllers\SubContractController;
use App\Http\Controllers\ProjectMaterialController;
use App\Http\Controllers\ProjectPriceRevisionController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\StockHistoryController;
use App\Models\Document;
use App\Models\ProjectActivity;
use App\Models\ProjectPriceRevision;

Auth::routes();

Route::get('/', function () {
    return redirect('login');
});


Route::get('/test', function () {
    $effectivityDate = \Carbon\Carbon::parse('2022-06-01');
    $diff = $effectivityDate->diffInDays(now());
    return $diff;
});

Route::post('dark-mode/update', [DarkModeController::class, 'update']);

Route::get('/storage/images/{image}', function ($image) {
    if(Storage::disk('public')->exists('images/' . $image)){
        $file = Storage::disk('public')->path('images/' . $image);
    }else{
        $file = public_path('images/placeholder.png');
    }
    return Response::file($file);
});



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function(){
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('profile', function () {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    })->name('profile');

    Route::resource('category', CategoryController::class);
    Route::resource('deduction', DeductionController::class);
    Route::resource('expense', ExpenseController::class);
    Route::resource('equipment', EquipmentController::class);
    Route::post('user/assign-of-cash-on-hand', [UserController::class, 'updateCashOnHandUser']);
    Route::resource('user', UserController::class);
    Route::put('user/{id}/profile', [UserController::class, 'profile'])->name('user.profile');
    
    Route::resource('delivery', DeliveryController::class);
    Route::resource('document',Document::class);

    Route::get('project/{id}/statistic', [ProjectController::class, 'statistic'])->name('project.statistic');
    Route::get('project/{id}/accomplishment', [ProjectController::class, 'accomplishment'])->name('project.accomplishment');
    Route::get('project/{id}/expenses', [ProjectController::class, 'expenses'])->name('project.expenses');
    Route::get('project/{id}/materials', [ProjectController::class, 'materials'])->name('project.materials');
    Route::get('project/{id}/sub-contract', [ProjectController::class, 'subContracts'])->name('project.sub-contract');
    Route::get('project/{id}/activity', [ProjectController::class, 'activity'])->name('project.activity');
    Route::get('project/{id}/price-revision', [ProjectController::class, 'priceRevision'])->name('project.price.revision');
    Route::post('project-price-revision', [ProjectPriceRevisionController::class, 'store'])->name('project-price-revision.store');
    Route::delete('project-price-revision/{id}', [ProjectPriceRevisionController::class, 'destroy'])->name('project-price-revision.destroy');
    Route::resource('project-activity', ProjectActivityController::class);
    Route::get('project/{id}/documents', [ProjectController::class, 'document'])->name('project.document');
    Route::post('project/{id}/document', [ProjectController::class, 'documentStore'])->name('project.document.store');
    Route::get('project/archieved', [ProjectController::class, 'archieved'])->name('project.archieved');
    Route::resource('project', ProjectController::class);
    Route::post('accomplishment-item/{accomplishment_item}/updateweight', [AccomplishmentItemController::class, 'updateWeightAccomplish'])->name('accomplisment-item.update.weight-accomplished');
    Route::post('accomplishment-item/{accomplishment_item}/updatecostbilling', [AccomplishmentItemController::class, 'updateCostBilling'])->name('accomplisment-item.update.cost-billing');
    Route::resource('accomplishment-item', AccomplishmentItemController::class)->only(['store','update', 'destroy']);
    Route::resource('monthly-achievement', MonthlyAchievementController::class)->only(['store','update', 'destroy']);
    Route::resource('project-material', ProjectMaterialController::class)->only(['store', 'destroy']);

    

    Route::get('/report/project-sumary', [App\Http\Controllers\ReportController::class, 'project'])->name('report.project');
    Route::get('/report/expenses', [App\Http\Controllers\ReportController::class, 'expenses'])->name('report.expenses');
    Route::get('/report/casher-summary', [App\Http\Controllers\ReportController::class, 'casher'])->name('report.ledger-summary');
    Route::resource('inventory', InventoryController::class);
    Route::resource('stock-history', StockHistoryController::class);
    Route::resource('material', MaterialController::class);
    Route::resource('role', RoleController::class);
    Route::resource('permission', PermissionController::class);
    Route::resource('warehouse', WarehouseController::class);
    Route::resource('motorpool', MotorpoolController::class);
    Route::resource('log', AuditTrailController::class);
    Route::resource('stock', StockController::class);
    Route::resource('sub-contract', SubContractController::class);
    Route::resource('personnel', PersonnelController::class);
    Route::resource('cash-in', CashInController::class);
    Route::resource('cash-advance', CashAdvanceController::class);
    Route::get('petty-cash/salary', [PettyCashController::class, 'salaryForm'])->name('petty-cash.salary.form');
    Route::post('petty-cash/salary', [PettyCashController::class, 'salarySubmit'])->name('petty-cash.salary.submit');
    Route::resource('ledger', LedgerController::class);
    Route::get('petty-cash/{id}/print', [PettyCashController::class, 'print'])->name('petty-cash.print');
    Route::resource('salary', SalaryController::class)->only(['create', 'store', 'edit', 'update']);
    Route::resource('petty-cash', PettyCashController::class);
});

include "prints.php";