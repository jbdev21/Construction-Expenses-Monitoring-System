<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Equipment::with(['category'])->latest();

        if ($request->q) {
            $query = $query->where("name", "LIKE", "%" . $request->q . "%");
        }

        if ($request->status) {
            $query = $query->whereStatus($request->status ?? 'functional');
        }

        $equipments = $query->paginate(15);

        return view('equipment.index', compact('equipments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::whereType('equipment')->get();
        return view('equipment.create', compact('categories'));
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
            'code'                  => 'required|unique:equipments,code',
            'name'                  => 'required',
            'rate_per_hour'         => 'required',
            'daily_rate'         => 'required',
            'plate_number'          => 'required'
        ]);

        $equipment = Equipment::create($request->except(['_token', 'thumbnail']));

        if($request->hasFile("thumbnail")){
            $thumbnail = $equipment->addImage($request->thumbnail, 'thumbnail');
            resizeImage($thumbnail->path, 250, 250, false);
        }

        return redirect()->route('equipment.index')->with('message', 'New Equipment Added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $equipment = Equipment::find($id)->load(['category', 'expenses']);
        $expenses = $equipment->expenses()->with('project')->latest()->paginate(25);
        return view('equipment.show', compact('equipment', 'expenses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $equipment = Equipment::find($id);

        $categories = Category::whereType('equipment')->get();
        return view('equipment.edit', compact('equipment', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipment $equipment)
    {

        $equipment->update($request->except(['_token']));

        return redirect()->route('equipment.index')->with('message', 'New Equipment Added!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipment = Equipment::find($id);
        $equipment->delete();

        return back()->with('delete', ' Record Deleted!');
    }
}
