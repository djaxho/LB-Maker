<?php

namespace App\Http\Controllers;

use App\BlogGroup;
use Illuminate\Http\Request;

class BlogGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modelStandardFields = with(new BlogGroup)->fillableAttributesForForms();

        // Get blog groups from all team members
        if(auth()->user()->team()) {
            $blogGroups = BlogGroup::whereInTeam(auth()->user()->team()->id)
                ->get()
                ->toArray();
        } else {
            $blogGroups = [];
        }

        return  view('models.index', [
            'modelName' => 'Blog-Groups',
            'modelKeys' => $modelStandardFields,
            'models' => $blogGroups
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
        $modelStandardFields = with(new BlogGroup)->fillableAttributesForForms();

        // Add attributes/fields with multiple options
        $modelSelectableFields = [];

        return  view('models.create', [
            'model' => 'Blog Group',
            'parentRoute' => 'blog-groups',
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
            "name" => 'required|string|min:3|max:100|unique:blog_groups',
            "mailchimp_key" => 'required'
        ]);

        $requestArray = $request->toArray();
        $requestArray['user_id'] = auth()->id();
        $requestArray['team_id'] = auth()->user()->team()->id;


        $blogGroup = BlogGroup::create($requestArray);

        return redirect("/blog-groups/{$blogGroup->id}");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BlogGroup  $blogGroup
     * @return \Illuminate\Http\Response
     */
    public function show(BlogGroup $blogGroup)
    {
        $this->authorize('update', $blogGroup);

        // Get fillable attributes/fields from model
        $modelStandardFields = $blogGroup->fillableAttributesForForms();

        $modelSelectableFields = [];

        $blogGroup->route = 'blog-groups';

        return  view('models.show', [
            'model' => $blogGroup,
            'modelStandardFields' => $modelStandardFields,
            'modelSelectableFields' => $modelSelectableFields
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BlogGroup  $blogGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(BlogGroup $blogGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BlogGroup  $blogGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlogGroup $blogGroup)
    {
        $this->validate($request, [
            "name" => 'required|string|min:3|max:100',
            "mailchimp_key" => 'required'
        ]);

        $blogGroup->update($request->toArray());

        return redirect("/blog-groups/{$blogGroup->id}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BlogGroup  $blogGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogGroup $blogGroup)
    {
        $this->authorize('delete', $blogGroup);

        $blogGroup->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Lead Box Deleted']);
        }

        return redirect('/blog-groups');
    }
}
