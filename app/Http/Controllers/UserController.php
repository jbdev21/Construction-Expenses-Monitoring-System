<?php

namespace App\Http\Controllers;

use App\Models\CashOnHand;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::query()->with('roles')->orderBy('name');

        if ($request->q) {
            $query = User::search($request->q);
        }
        
        if ($request->status != '') {
            $query = User::whereActive($request->status ?? 0);

        }

        $roles = Role::all();

        $users = $query->paginate(15);

        return view('user.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
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
            'name'              => 'required',
            'address'           => 'required',
            'contact_number'    => 'required',
            'position'          => 'required',
            'username'          => 'required|unique:users,username'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->contact_number = $request->contact_number;
        $user->position = $request->position;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);

        $user->assignRole(Role::find($request->role));

        $user->save();

        return redirect()->route('user.index')->with('success', 'User has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles'));
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
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->contact_number = $request->contact_number;
            $user->position = $request->position;
            $user->username = $request->username;

            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $user->removeRole(Role::find($user->roles->first()->id)); //remove first the role of the user
            $user->assignRole(Role::find($request->role));

            return redirect()->route('user.index')->with('success', 'User has been updated.');
    }

    public function profile(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($request->hasFile('thumbnail')) {
            $user->removeThumbnail();
            $thumbnail = $user->addImage($request->thumbnail, 'thumbnail');
            resizeImage($thumbnail->path, 250, 250, false);
        }

        return back()->with('success', 'Your Account has been updated!');
        
    }

    public function updateCashOnHandUser(Request $request)
    {
        $exists = CashOnHand::first();
        if($exists) {
            $exists->update(['user_id', $request->user]);
        }else{
            CashOnHand::create(['user_id' => $request->user, 'amount' => 0]);
        }
        return back()->with('success', 'Cash on hand has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return back()->with('delete', ' Record Deleted!');
    }
}
