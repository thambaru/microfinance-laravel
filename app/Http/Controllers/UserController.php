<?php

namespace App\Http\Controllers;

use App\Libraries\Common;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        if (empty($request->ajax))
            return view('users.index');

        $users = User::whereRoleIs('rep')->get();

        return compact('users');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isEdit = !empty($request->id);

        $fields = [
            'full_name' => ['required', 'max:255'],
            'name' => ['required', 'max:255'],
            'nic' => 'required',
            'address' => 'required',
            'commiss_perc' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'phone_num' => 'required|min:10',
        ];

        $user = $isEdit ? User::find($request->id) : new User();

        if ($isEdit) {
            if ($user->email != $request->email)
                $fields['email'] = 'email|unique:users';
            else
                $fields['email'] = '';
        }

        $request->validate($fields);

        foreach ($fields as $field => $val)
            $user->$field = $request->$field;

        $user->username = Str::random(15);
        $user->password = Hash::make($request->password);

        $user->save();

        $user->attachRole(Common::$userRoles['rep']);

        return redirect()->back()->with('status', "success");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.form', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->is_active = 0;
        $user->save();

        return redirect()->route('users.index')->with('status', "$user->full_name was deleted.");
    }
}
