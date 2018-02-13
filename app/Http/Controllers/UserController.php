<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all()->toArray();

        $modelStandardFields = with(new User)->fillableAttributesForForms();

        return  view('models.index', [
            'modelName' => 'Users',
            'modelKeys' => $modelStandardFields,
            'models' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        // Get fillable attributes/fields from model
        $modelStandardFields = $user->fillableAttributesForForms();

        // Add attributes/fields with multiple options
        $modelSelectableFields = [];

        $user->route = 'users';

        return  view('models.show', [
            'model' => $user,
            'modelStandardFields' => $modelStandardFields,
            'modelSelectableFields' => $modelSelectableFields
        ]);
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
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            "name" => 'required|string|min:3|max:100',
            "email" => "required|email|unique:users,email,{$user->id}",
            "profession" => 'required|string',
            "about" => 'required|string'
        ]);

        $user->update($request->toArray());

        return redirect("/users/{$user->id}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'User Deleted']);
        }

        return redirect('/users');
    }
}
