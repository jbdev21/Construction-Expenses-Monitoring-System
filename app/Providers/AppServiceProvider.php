<?php

namespace App\Providers;

use App\Models\AccomplishmentItem;
use App\Models\CashAdvance;
use App\Models\Stock;
use App\Models\Project;
use App\Models\Category;
use App\Models\Delivery;
use App\Models\DeliveryMaterialPivot;
use App\Models\Material;
use App\Models\Equipment;
use App\Models\Expense;
use App\Models\Image;
use App\Models\Ledger;
use App\Models\MaterialReStockPivot;
use App\Models\MonthlyAchievement;
use App\Models\PettyCash;
use App\Models\Warehouse;
use App\Observers\AccomplishmentItemObserver;
use App\Observers\CashAdvanceObserver;
use App\Observers\StockObserver;
use App\Observers\ProjectObserver;
use App\Observers\CategoryObserver;
use App\Observers\DeliveryMaterialPivotObserver;
use App\Observers\DeliveryObserver;
use App\Observers\MaterialObserver;
use App\Observers\EquipmentObserver;
use App\Observers\ExpenseObserver;
use App\Observers\ImageObserver;
use App\Observers\LedgerObserver;
use App\Observers\MaterialReStockPivotObserver;
use App\Observers\MonthlyAchievementObserver;
use App\Observers\PettyCashObserver;
use App\Observers\WarehouseObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::preventLazyLoading(! app()->isProduction());
        Paginator::useBootstrap();

        Material::observe(MaterialObserver::class);
        Category::observe(CategoryObserver::class);
        Equipment::observe(EquipmentObserver::class);
        Warehouse::observe(WarehouseObserver::class);
        Stock::observe(StockObserver::class);
        Project::observe(ProjectObserver::class);
        Expense::observe(ExpenseObserver::class);
        Image::observe(ImageObserver::class);
        Delivery::observe(DeliveryObserver::class);
        DeliveryMaterialPivot::observe(DeliveryMaterialPivotObserver::class);
        MaterialReStockPivot::observe(MaterialReStockPivotObserver::class);
        Ledger::observe(LedgerObserver::class);
        PettyCash::observe(PettyCashObserver::class);
        CashAdvance::observe(CashAdvanceObserver::class);
        AccomplishmentItem::observe(AccomplishmentItemObserver::class);
        MonthlyAchievement::observe(MonthlyAchievementObserver::class);

        \Spatie\Flash\Flash::levels([
            'success' => 'alert-success',
            'warning' => 'alert-warning',
            'error' => 'alert-error',
        ]);

        Http::macro('server', function () {
            return Http::accept('application/json')->baseUrl(config("app.online_server_url"));
        });

        RateLimiter::for('nolimit', function ($job) {
            return Limit::none();
        });

        // LogViewer::auth(function ($request) {
        //     return $request->user() ? true : false;
        // });
    }
}
