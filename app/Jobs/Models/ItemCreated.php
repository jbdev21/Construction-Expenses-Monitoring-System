<?php

namespace App\Jobs\Models;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ItemCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $table, $data;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($table, $data)
    {
        $this->table = $table;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::accept('application/json')
                ->post(config("app.online_server_url") . '/store', [
                    'table' => $this->table,
                    'data'  => $this->data,
                ]);

        if($response->status() != 200){
            return 0;
        }
    }
}
