<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = auth()->user()->teams()->get()->toArray();
        $modelStandardFields = with(new Team)->fillableAttributesForForms();

        return  view('models.index', [
            'modelName' => 'Teams',
            'modelKeys' => $modelStandardFields,
            'models' => $teams
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get fillable attributes/fields from model
        $modelStandardFields = with(new Team)->fillableAttributesForForms();

        // Add attributes/fields with multiple options
        $modelSelectableFields = [];

        return  view('models.create', [
            'model' => 'Team',
            'parentRoute' => 'teams',
            'modelStandardFields' => $modelStandardFields,
            'modelSelectableFields' => $modelSelectableFields
        ]);
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
            "name" => 'required|string|min:3|max:100|unique:teams',
            "label" => 'required|string',
            "about" => 'required|string'
        ]);

        $team = Team::create($request->toArray());

        auth()->user()->joinTeam($team);

        return redirect("/teams/{$team->id}");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        $this->authorize('view', $team);

        $modelStandardFields = $team->fillableAttributesForForms();

        $modelSelectableFields = [];

        $team->route = 'teams';

        return  view('models.show', [
            'model' => $team,
            'modelStandardFields' => $modelStandardFields,
            'modelSelectableFields' => $modelSelectableFields
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $this->authorize('view', $team);

        $this->validate($request, [
            "name" => 'required|string|min:3|max:100|unique:teams',
            "label" => 'required|string',
            "about" => 'required|string'
        ]);

        $team->update($request->toArray());

        return redirect("/teams/{$team->id}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $this->authorize('view', $team);

        $team->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Team Deleted']);
        }

        return redirect('/teams');
    }
}
