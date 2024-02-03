<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Project;
use App\Models\Equipment;
use App\Models\Material;
use App\Models\SubContract;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Expense::latest();

        if($request->q){
            $query->where('items', 'LIKE', '%' . $request->q . '%');
        }

        if($request->date_from && !$request->date_to){
            $start = Carbon::parse($request->date_from);
            $end = Carbon::parse($request->date_to);
            $query->whereBetween('created_at', [$start->format('Y-m-d'), now()->addDay(1)])->get();
        }

        if($request->date_from && $request->date_to){
            $start = Carbon::parse($request->date_from);
            $end = Carbon::parse($request->date_to);
            $query->whereBetween('created_at', [$start->format('Y-m-d'), $end->addDay(1)->format('Y-m-d')])->get();
        }

        if($request->type){
            $query->where('type', $request->type);
        }
       
        $expenses = $query->whereIn('type', config('system.category.general_expenses'))->with(['expensable', 'project'])->paginate(30);
        return view('expense.index', compact("expenses"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->project){
            $projects = Project::findOrFail($request->project);
        }else{
            $projects = "";
        }
        $categories = config('system.category.expenses');
        return view('expense.create', compact('categories', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'items' => ['required', 'string'],
            'amount' => ['required']
        ]);


        DB::beginTransaction();

        try{
            $expense = Expense::create([
                'items' => $request->items,
                'amount' => $request->amount,
                'unit_quantity' => $request->unit_quantity,
                'unit_price' => $request->unit_price,
                'type' => $request->type,
                'effectivity_date' => $request->effectivity_date,
                'project_id' => $request->project_id
            ]);
    
    
            if($request->equipment_id){
                $equipment = Equipment::findOrFail($request->equipment_id);
                $equipment->expenses()->save($expense);
            }

            if($request->sub_contract_id){
                $subCon = SubContract::findOrFail($request->sub_contract_id);
                $subCon->expenses()->save($expense);
            }

            if($request->material_id){
                $material = Material::findOrFail($request->material_id);
                $warehouse = Warehouse::findOrFail($request->warehouse_id);
                $stock = $warehouse->stocks()->where("material_id", $material->id)->first();
    
                if($stock){
                    if($stock->quantity < $request->unit_quantity){
                        throw new \Exception($material->name . " not enough stock");
                    }
    
                    $stock->decrement("quantity", $request->unit_quantity); // decrement the quantity from stock
    
                }else{
                    throw new \Exception($material->name . " is out of stock in ". $warehouse->name);
                }
    
                $material->expenses()->save($expense);
            }
    
            DB::commit();

            flash()->success("Expense saved succefully!");
            
            if($request->redirect){
                return redirect($request->redirect);
            }
    
            return redirect()->route('expense.index');

        }catch (\Exception $exception){
            DB::rollback();
            flash()->warning($exception->getMessage());
            return back();
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('delete', ' Record Deleted!');
    }
}
