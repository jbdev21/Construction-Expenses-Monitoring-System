<?php

namespace App\Console\Commands;

use App\Jobs\CreateJsonSql;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateServerWithNewDailyDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-server-with-new-daily-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending data to the server in the daily basis. This include all data aside from the expenses and pettycash';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $excemptedTables = [
            'users',
            'migrations',
            'failed_jobs',
            'jobs',
            'password_resets',
            'websockets_statistics_entries',
            'role_has_permissions',
            'model_has_roles',
            'model_has_permissions',
        ];

        $tables = DB::select("SHOW TABLES");
        foreach($tables as $table){
            foreach($table as $key => $tableName){
                if(!in_array($tableName, $excemptedTables)){
                    CreateJsonSql::dispatch($tableName);
                }
            }
        }

        return 0;
    }
}
