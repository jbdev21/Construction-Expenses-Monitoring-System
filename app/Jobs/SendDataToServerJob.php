<?php

namespace App\Jobs;

use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SendDataToServerJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file, $table; 


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $table, string $file)
    {
        $this->table = $table;
        $this->file = $file;
    }



    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = json_decode(Storage::disk('public')->get($this->file));
       
        $response = Http::accept('application/json')
                        ->post(config("app.online_server_url") . '/store', [
                            'table' => $this->table,
                            'data'  => $data,
                        ]);

        if($response->status() != 200){
            Log::error($response->body());
            return 0;
        }

        Storage::disk('public')->delete($this->file);
        sleep(30);
        return 0;
    }

}
