<?php

namespace App\Jobs;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateJsonSql implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $table;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $noUpdatedAtTables = [
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
            'jobs'
        ];

        $table = $this->table;
        
        DB::table($table)
            ->when(!in_array($table, $noUpdatedAtTables), function($query){
                $query
                    ->whereDate("updated_at", now())
                    ->orderBy("updated_at", "DESC");
            })
            ->when($table == "model_has_permissions", function($query){
                $query->orderBy("permission_id", "DESC");
            })
            ->when($table == "model_has_roles", function($query){
                $query->orderBy("role_id", "DESC");
            })
            ->when($table == "role_has_permissions", function($query){
                $query->orderBy("role_id", "DESC");
            })
            ->when($table == "jobs", function($query){
                $query->orderBy("id", "DESC");
            })
            ->chunk(150, function($records) use ($table) {
                $file = "sql-json/" . $table . "-" . Str::uuid() . ".json";
                Storage::disk('public')->put(
                    $file,
                    json_encode($records)
                );
                // SendDataToServerJob::dispatch($table, $file);
            });
        

       return 0;
    }
}
