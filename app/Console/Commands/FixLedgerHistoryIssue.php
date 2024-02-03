<?php

namespace App\Console\Commands;

use App\Models\CashOnHand;
use App\Models\Ledger;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FixLedgerHistoryIssue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fix-ledger-history-issue {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To Fix all of the ledger history issue from given date';

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
        $date = Carbon::parse($this->argument('date'));
        if($this->confirm("You are about to rehistory the ledger from " . $date->format("M d, Y") .". Are you sure to continue?")){ 
            $startingDate = $date;
            $lastLedger = Ledger::whereDate("effectivity_date", '<=', $startingDate->subDays(1))
                            ->whereIn("type", ['credit', 'debit'])
                            ->orderBy("effectivity_date", "DESC")
                            ->orderBy('id', 'DESC')  
                            ->first();

            $cashOnhand = CashOnHand::first();

            $cashOnhand->update(['amount' => $lastLedger->amount ?? 0]);

            $ledgers = Ledger::where('effectivity_date', '>=', $startingDate)
                    ->whereIn("type", ['credit', 'debit'])
                    ->orderBy("effectivity_date", "ASC")
                    ->orderBy('id', 'ASC')        
                    ->get();

            foreach($ledgers as $ledger){
                // $onhand = $cashOnhand->refresh();
                // if($ledger->type == "credit"){
                //     $onhand->increment('amount', $ledger->amount);
                // }else{
                //     $onhand->decrement('amount', $ledger->amount);
                // }

                if($ledger->type == "credit"){
                    $cashOnhand->increment('amount', $ledger->amount);
                }elseif($ledger->type == "debit"){
                    $cashOnhand->decrement('amount', $ledger->amount);
                }

                $ledger->update(['onhand_amount' => CashOnHand::first()->amount]);
            }
        }

        return 0;
    }
}
