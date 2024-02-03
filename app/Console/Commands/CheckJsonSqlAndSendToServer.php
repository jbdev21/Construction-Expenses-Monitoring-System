<?php

namespace App\Console\Commands;

use App\Jobs\SendDataToServerJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckJsonSqlAndSendToServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-sql-json-and-send-to-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $files = Storage::disk('public')->files('sql-json');
        foreach($files as $file){
            $table = $this->getTableName($file);
            SendDataToServerJob::dispatch($table, $file);
        }
    }

    function getTableName($file){
        $scrape = str_replace("sql-json/", "", $file);
        return explode("-", $scrape)[0];
    }
}
