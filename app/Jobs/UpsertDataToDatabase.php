<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpsertDataToDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $table;
    public $data;
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
        DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        try {
            DB::table($this->table)->upsert($this->data, ['id']);
            DB::commit();
            return response()
                ->json([
                    'message' => "succesfully updated " . count($this->data) . ' records in ' . $this->table . " table",
                    'status' => 'success'
                ], 200);
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (Exception $exception) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->release(15);
        }
    }
}
