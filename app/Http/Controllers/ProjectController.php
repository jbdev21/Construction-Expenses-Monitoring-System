<?php

namespace App\Http\Controllers;

use App\Models\AccomplishmentItem;
use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Project;
use App\Models\Category;
use App\Models\Deduction;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Project::whereIn('status', ['ongoing', 'draft']);

        if ($request->q) {
            $query = Project::where('name', 'LIKE', '%' . $request->q . '%')
                ->with('category')->whereIn('status', ['ongoing', 'draft']);
        }

        if ($request->status != '') {
            $query->whereStatus($request->status ?? 'ongoing');
        }

        if ($request->project_year) {
            $query->whereProjectYear($request->project_year);
        }

        
        $projects = $query
                    ->orderBy('project_year', 'DESC')
                    ->with(['priceRevisions', 'category'])->paginate(25);

        return view('project.index', compact('projects'));
    }

    public function archieved(Request $request)
    {
        $query = Project::whereStatus("finished")->orderBy('name');

        if ($request->q) {
            $query->where("name", 'LIKE', '%' . $request->q . '%');
        }

        $projects = $query->with(['priceRevisions', 'category'])->paginate(25);

        return view('project.archieved', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $deductions = Deduction::orderBy('name')->get();
        $categories = Category::whereType('project')->get();
        return view('project.create', compact('categories','deductions'));
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
            'name'                          => ['required'],
            'contract_id'                   => ['required'],
            'contract_amount'                => ['required'],
            'ntp_date'                      => ['required'],
            'project_duration_years'        => ['required'],
            'project_duration_months'       => ['required'],
            'project_duration_days'         => ['required'],
            'expiry_date'                   => ['required'],
            'status'                        => ['required'],
            'category_id'                   => ['required'],
        ]);

        $project = new Project;
        $project->name = $request->name;
        $project->contract_id = $request->contract_id;
        $project->contract_amount = $request->contract_amount;
        $project->ntp_date = $request->ntp_date;
        $project->project_duration_years = $request->project_duration_years;
        $project->project_duration_months = $request->project_duration_months;
        $project->project_duration_days = $request->project_duration_days;
        $project->expiry_date = $request->expiry_date;
        $project->project_year = $request->project_year;
        $project->contractor_licence = $request->contractor_licence;
        $project->status = $request->status;
        $project->category_id = $request->category_id;

        $project->old_expense_labors = $request->old_expense_labors ?? 0;
        $project->old_expense_materials = $request->old_expense_materials ?? 0;
        $project->old_expense_rentals = $request->old_expense_rentals ?? 0;
        $project->old_expense_others = $request->old_expense_others ?? 0;
        $project->save();

        $project->deductions()->sync($request->deductions);
        return redirect()->route('project.index')->with('message', 'New project Added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project    = Project::find($id);

        return view('project.show', compact('project'));
    }

    public function accomplishment(Request $request, $id){
        
        $project = Project::findOrFail($id)->load('accomplishmentItems');
        if($request->whole){
            return view('project.show.accomplishment-whole', compact('project'));
        }
        $accomplishmentItemsGroups = AccomplishmentItem::where("type", 'group')
                                            ->where("project_id", $project->id)
                                            ->get();
        $accomplishmentItems = $project->accomplishmentItems()->get();
        $monthlyAchievements = $project->monthlyAchievements()->orderBy("complete_month_date")->get();
        return view('project.show.accomplishment', compact('project', 'accomplishmentItems', 'accomplishmentItemsGroups', 'monthlyAchievements'));
    }


    public function expenses(Request $request, $id){
        $project = Project::find($id)->load('expenses');
        $query = $project
                    ->expenses()
                    ->orderBy('effectivity_date', 'DESC')->orderBy("id", 'DESC');

        if ($request->q) {
            $query->where('items', 'LIKE', '%' . $request->q . '%');
        }

        if ($request->date_from && !$request->date_to) {

            $start = Carbon::parse($request->date_from);
            $end = Carbon::parse($request->date_to);

            $query->whereBetween('created_at', [$start->format('Y-m-d'), now()->addDay(1)])->get();
        }

        if ($request->date_from && $request->date_to) {

            $start = Carbon::parse($request->date_from);
            $end = Carbon::parse($request->date_to);

            $query->whereBetween('created_at', [$start->format('Y-m-d'), $end->addDay(1)->format('Y-m-d')])->get();
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $laborQuery     = clone $query;
        $totalLabor     = $laborQuery->where('type', 'labor')->sum('amount');

        $othersQuery    = clone $query;
        $totalOthers    = $othersQuery->where('type', 'others')->sum('amount');

        $rentalsQuery   = clone $query;
        $totalRentals   = $rentalsQuery->where('type', 'rental equipment')->sum('amount');

        $materialQuery  = clone $query;
        $totalMaterial  = $materialQuery->where('type', 'material')->sum('amount');

        $forSum = clone $query;
        $sum = $forSum->sum("amount");
        
        $expenses   = $query->paginate(45);

        return view('project.show.expenses', compact('project', 'expenses', 'totalLabor', 'totalOthers', 'totalMaterial', 'totalRentals', 'sum'));
    }



    public function subContracts(Request $request, $id){
        $project    = Project::find($id);

        $subContracts = $project->subContracts()->latest()->get();

        return view('project.show.sub-contract', compact('project', 'subContracts'));
    }

    public function materials(Request $request, $id){
        $project = Project::find($id)->load(['materials.material', 'category']);
        $total = $project->materials->sum('total_price');

        $materials = $project->materials()
                    ->select("*")
                    ->addSelect(DB::raw("(select SUM(`unit_quantity`) from `expenses` WHERE `expensable_type` = 'App\\\Models\\\Material' and `project_id`=`project_materials`.`project_id` and `expensable_id` = `project_materials`.`material_id`) as delivered_quantity"))
                    ->addSelect(DB::raw("(select SUM(`amount`) from `expenses` WHERE `expensable_type` = 'App\\\Models\\\Material' and `project_id`=`project_materials`.`project_id` and `expensable_id` = `project_materials`.`material_id`) as total_expense"))
                    ->get();
        return view('project.show.materials', compact('project', 'total', 'materials'));
    }

    public function statistic(Request $request, $id){
        $project    = Project::find($id);

        foreach(config('system.category.expenses') as $category){
            $expenses[$category] = $project->sumExpenses($category);
        }

        return view('project.show.statistic', compact('project', 'expenses'));
    }

    public function priceRevision(Request $request, $id){
        $project    = Project::find($id);

        $priceRevisions = $project->priceRevisions()->latest()->paginate(25);

        return view('project.show.price-revision', compact('project', 'priceRevisions'));
    }

    public function document(Request $request, $id){
        $project = Project::find($id)->load(['documents']);
        $documents = $project->documents()->with('user')->latest()->paginate(25);
        return view('project.show.document', compact('project', 'documents'));
    }

    public function documentStore(Request $request, $id){
        $project = Project::find($id);
        $project->documents()->create([
            'user_id' => $request->user()->id,
            'filename' => $request->file_name,
            'path' => $request->document->store('documents', 'public'),
        ]);
        return redirect()->route('project.document', $project->id)->with('message', 'New document Added!');
    }

    public function activity(Request $request, $id){
        $project = Project::find($id);    
        $activities = $project->activities()->latest()->paginate(25);
        return view('project.show.activity', compact('project', 'activities'));    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find($id);
        $categories = Category::whereType('project')->get();
        $deductions = Deduction::orderBy('name')->get();
        return view('project.edit', compact('project', 'categories', 'deductions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $project->update($request->except(['_token', 'deductions']));
        $project->deductions()->sync($request->deductions);

        return redirect()->route('project.index')->with('message', 'Project Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->expenses()->delete();
        $project->activities()->delete();
        $project->subContracts()->delete();
        $project->materials()->delete();
        $project->deductions()->detach();
        $project->delete();

        flash()->success('Project deleted!');
        return back()->with('delete', ' Record Deleted!');
    }
}
