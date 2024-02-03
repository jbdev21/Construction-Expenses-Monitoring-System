<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Material;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Material::query()
                ->select(
                    '*',
                    DB::raw("(select sum(`quantity`) from `stocks` where `stocks`.`material_id` = `materials`.`id`) as current_stock")
                    )
                ->with(['category'])
                ->when($request->q, function($query) use ($request){
                    $query->where('name', 'LIKE', '%'. $request->q . '%')
                            ->orWhere('code', 'LIKE', '%'. $request->q . '%');
                })
                ->when($request->category, function($query) use ($request){
                    $query->whereCategoryId($request->category ?? 0);
                })
                ->orderBy('name');

        $categories = Category::whereType('material')->get();
        $count = $request->q ? count($query->get()) : $query->count();

        $materials = $query->paginate(50);
        $warehouses = Warehouse::whereIsActive(1)->get();
        return view('material.index', compact('materials', 'categories', 'count', 'warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::whereType('material')->get();
        
        return view('material.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMaterialRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'           => 'required',
            'code'           => 'required',
            'unit'           => 'required',
            'price'          => 'required',
        ]);

        $material = new Material;
        $material->code = $request->code;
        $material->name = $request->name;
        $material->unit = $request->unit;
        $material->price = $request->price;
        $material->description = $request->description;
        $material->category_id = $request->category_id;

        $material->save();

        if($request->stock){
            $warehouse = Warehouse::find($request->warehouse_id);
            Stock::create([
                'warehouse_id' => $request->warehouse_id,
                'material_id' => $material->id,
                'quantity' => $request->stock
            ]);
        }

        return redirect()->route('material.index')->with('success', 'Material has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function edit(Material $material)
    {
        $categories = Category::whereType('material')->get();

        return view('material.edit', compact('categories', 'material'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMaterialRequest  $request
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $material = Material::find($id);
        $material->code = $request->code;
        $material->name = $request->name;
        $material->unit = $request->unit;
        $material->price = $request->price;
        $material->description = $request->description;
        $material->category_id = $request->category_id;

        $material->save();

        return redirect()->route('material.index')->with('success', 'Material has been Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        $material->delete();

        return back()->with('delete', ' Record Deleted!');
    }
}
