<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Expense;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Delivery::query()->with(['deliverableFrom', 'deliverableTo']);
        $deliveries = $query->latest()->paginate();
        return view('delivery.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $typeList = ['Project', 'Motorpool'];
        return view("delivery.create", compact('typeList'));
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
            'deliverable_from_type' => ['required'],
            'deliverable_to_type' => ['required'],
            'deliverable_from_id' => ['required'],
            'deliverable_to_id' => ['required'],
            'material_ids' => ['required'],
            'quantity' => ['required'],
            'prices' => ['required'],
        ]);

        DB::beginTransaction();

        try{
            // get the origin
            $originString = $request->deliverable_from_type;
            $originInstance = new $originString();
            $origin = $originInstance::find($request->deliverable_from_id);

            // get the destination
            $destinationString = $request->deliverable_to_type;
            $destinationInstance = new $destinationString();
            $destination = $destinationInstance::find($request->deliverable_to_id);

            $delivery = new Delivery;
            $delivery->description = $request->description ?? "hehe";
            $delivery->setFromAndTo($origin, $destination);
            $delivery->amount = 0;
            $delivery->effectivity_date = $request->effectivity_date ?? now()->format('Y-m-d');
            $delivery->save();

            $counter = 0;
            foreach($request->material_ids as $material){
                $amount = $request->prices[$counter] * $request->quantity[$counter];
                $materialModel = Material::find($material);
                $delivery
                    ->materials()
                    ->attach([
                        $material => [
                            'quantity'          => $request->quantity[$counter],
                            'price_per_unit'    => $request->prices[$counter],
                            'amount'            => $amount,
                            'remarks'           => $request->remarks[$counter],
                            ]]);
                $counter++;
            }
            
            DB::commit();
            flash()->success('Delivery created successfully');
            return redirect()->route('delivery.index');

        }catch (\Exception $exception){
            DB::rollback();
            flash()->warning($exception->getMessage());
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery $delivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit(Delivery $delivery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Delivery $delivery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Delivery $delivery)
    {
        //
    }
}
