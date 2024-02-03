<?php

namespace App\Http\Controllers;

use App\Models\MaterialReStockPivot;
use App\Models\ReStock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $histories = ReStock::latest()
                ->when($request->warehouse, function($query) use ($request){
                    $query->whereWareHouseId($request->warehouse);
                })
                ->with(['materials', 'warehouse'])
                ->withCount(['materials'])
                ->orderBy('effectivity_date', 'DESC')   
                ->paginate();

        $warehouses = Warehouse::all();

        return view('stock-history.index', compact("histories", 'warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stock-history.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'warehouse_id'      => ['required'],
            'supplier'          => ['required'],
            'effectivity_date'  => ['required'],
            'material_ids'      => ['required', 'array'],
            'quantity'          => ['required', 'array'],
        ]);

        DB::beginTransaction();

        try{
            $restock = ReStock::create([
                'warehouse_id' => $request->warehouse_id,
                'supplier' => $request->supplier,
                'effectivity_date' => $request->effectivity_date,
                'description' => $request->description,
            ]);

            foreach($request->material_ids as $index => $material){
                $restock->materials()->attach(
                    [
                        $material => [
                            'quantity' => $request->quantity[$index],
                        ]
                    ]);
            }

            DB::commit();
            flash()->success('Restock created successfully');
            return $request->redirect 
                        ? redirect($request->redirect)
                        : redirect()->route('stock-history.index');

        }catch (\Exception $exception){
            DB::rollback();
            flash()->warning($exception->getMessage());
            return back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $history = ReStock::find($id)->load(['materials', 'warehouse']);

        return view('stock-history.show', compact('history'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return DB::transaction(function() use ($id){
            $restock = ReStock::find($id);
            foreach(MaterialReStockPivot::where("re_stock_id", $restock->id)->get() as $item){
                $item->delete();
            }
            
            $restock->delete();

            flash()->success("Stock history deleted successfully!");
            return back();
        });
    }
}
