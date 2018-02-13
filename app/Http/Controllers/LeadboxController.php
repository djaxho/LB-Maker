<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Leadbox;
use Illuminate\Http\Request;

class LeadboxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modelStandardFields = with(new Leadbox)->fillableAttributesForForms();

        // Get blog groups from all team members
        $leadboxes = Leadbox::whereInTeam(auth()->user()->team()->id)
            ->get()
            ->toArray();

        return  view('models.index', [
            'modelName' => 'Lead Boxes',
            'modelKeys' => $modelStandardFields,
            'models' => $leadboxes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get blog groups from all team members
        $teamBlogs = Blog::whereInTeam(auth()->user()->team()->id)
            ->get()
            ->toArray();

        // Get fillable attributes/fields from model
        $modelStandardFields = with(new Leadbox)->fillableAttributesForForms();

        // Add attributes/fields with multiple options
        $modelSelectableFields = [
            'blog_id' => [
                'fieldLabel' => 'Blog',
                'fieldOptions' => $teamBlogs
            ]
        ];

        return  view('models.create', [
            'model' => 'Leadbox',
            'parentRoute' => 'leadboxes',
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
            "blog_id" => 'required|int',
            "name" => 'required|string|min:3|max:100',
            "url" => 'required',
            "main_text" => 'nullable|string',
            "button_text" => 'nullable|string'
        ]);

        $request = $request->toArray();
        $request['user_id'] = auth()->id();
        $request['team_id'] = auth()->user()->team()->id;

        $leadbox = Leadbox::create($request);

        return redirect("/leadboxes/{$leadbox->id}");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leadbox  $leadbox
     * @return \Illuminate\Http\Response
     */
    public function show(Leadbox $leadbox)
    {
        $this->authorize('update', $leadbox);

        // Get fillable attributes/fields from model
        $modelStandardFields = $leadbox->fillableAttributesForForms();

        // Get blog groups from all team members
        $teamBlogs = Blog::whereInTeam(auth()->user()->team()->id)
            ->get();

        // Add attributes/fields with multiple options
        $modelSelectableFields = [
            'blog_id' => [
                'fieldLabel' => 'Blog',
                'fieldOptions' => $teamBlogs
            ]
        ];

        $leadbox->route = 'leadboxes';

        return  view('models.show', [
            'model' => $leadbox,
            'modelStandardFields' => $modelStandardFields,
            'modelSelectableFields' => $modelSelectableFields
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Leadbox  $leadbox
     * @return \Illuminate\Http\Response
     */
    public function edit(Leadbox $leadbox)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Leadbox  $leadbox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leadbox $leadbox)
    {
        $this->validate($request, [
            "name" => 'required|string|min:3|max:100',
            "url" => 'required',
            "main_text" => 'nullable|string',
            "button_text" => 'nullable|string'
        ]);

        $leadbox->update($request->toArray());

        return redirect("/leadboxes/{$leadbox->id}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leadbox  $leadbox
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leadbox $leadbox)
    {
        $this->authorize('delete', $leadbox);

        $leadbox->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Leadbox Deleted']);
        }

        return redirect('/leadboxes');
    }
}
