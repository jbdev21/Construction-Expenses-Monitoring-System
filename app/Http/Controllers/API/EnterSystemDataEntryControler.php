<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\UpsertDataToDatabase;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr;

class EnterSystemDataEntryControler extends Controller
{

    function checkLastId(Request $request){
        try{
            return DB::table($request->table)->latest()->first()->id ?? 0;
        }catch(Exception $exception){
            return response()->json([], 422);
        }
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadJSON(Request $request)
    {
        $this->validate($request,[
            'json_file' => ['required']
        ]);

        $jsonFile = $request->file('json_file')->store('json-files');
        $table = pathinfo($request->file("json_file")->getClientOriginalName(), PATHINFO_FILENAME);
        $file = Storage::get($jsonFile);
        $chuncked = collect(json_decode($file))->map(fn($e) => collect($e)->toArray())->chunk(100)->toArray();
        foreach($chuncked as $data){
            UpsertDataToDatabase::dispatch($table, $data);
        }   
        Storage::delete($jsonFile);    
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'table' => ['required', 'string'],
            'data' => ['required', 'array']
        ]);

        DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        try{
            DB::table($request->table)->upsert($request->data, ['id']);
            DB::commit();
            return response()
                ->json([
                    'message' => "succesfully updated " . count($request->data) . ' records in ' . $request->table . " table",
                    'status' => 'success'
                ], 200);
                DB::statement('SET FOREIGN_KEY_CHECKS=1');    
        }catch(Exception $exception){
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()
                ->json([
                    'message' => $exception->getMessage(),
                    'status' => 'success'
                ], 422);
        }
    }
}
