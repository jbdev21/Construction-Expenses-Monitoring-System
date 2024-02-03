<?php

namespace App\Http\Controllers;

use App\Models\Motorpool;
use Illuminate\Http\Request;

class MotorpoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Motorpool::query()->orderBy('name');

        if ($request->q) {
            $query = Motorpool::search($request->q);
        }

        if ($request->status != '') {
            $query = Motorpool::whereIsActive($request->status ?? 0);
        }

        $motorpools = $query->paginate(15);

        return view('motorpool.index', compact('motorpools'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('motorpool.create');
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
            'name'                  => 'required',
            'address'               => 'required',
            'contact_number'        => 'required'
        ]);

        Motorpool::create($request->except(['_token']));

        return redirect()->route('motorpool.index')->with('message', 'New Motorpool Added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $motorpool = Motorpool::find($id);
        return view('Motorpool.show', compact('motorpool'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $motorpool = Motorpool::find($id);
        return view('motorpool.edit', compact('motorpool'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Motorpool $motorpool)
    {
        $motorpool->update($request->except(['_token']));

        return redirect()->route('motorpool.index')->with('message', 'New Motorpool Added!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Motorpool $motorpool)
    {
        $motorpool->delete();

        return back()->with('delete', ' Record Deleted!');
    }
}
