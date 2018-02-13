<?php

namespace App\Http\Controllers;

use App\Blog;
use App\BlogGroup;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modelStandardFields = with(new Blog)->fillableAttributesForForms();

        // Get blog groups from all team members
        $blogs = Blog::whereInTeam(auth()->user()->team()->id)
            ->get()
            ->toArray();

        return  view('models.index', [
            'modelName' => 'Blogs',
            'modelKeys' => $modelStandardFields,
            'models' => $blogs
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
        $teamBlogGroups = auth()->user()->team()->blogGroups()->get()->toArray();

        // Get fillable attributes/fields from model
        $modelStandardFields = with(new Blog)->fillableAttributesForForms();

        // Add attributes/fields with multiple options
        $modelSelectableFields = [
            'blog_group_id' => [
                'fieldLabel' => 'Blog Group',
                'fieldOptions' => $teamBlogGroups
            ]
        ];

        return  view('models.create', [
            'model' => 'blog',
            'parentRoute' => 'blogs',
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
            "name" => 'required|string|min:3|max:100|unique:blogs',
            "blog_group_id" => 'required|int',
            "url" => 'required',
            "main_text" => 'required|string',
            "button_text" => 'required|string',
            "mailchimp_list" => 'required',
            "mailchimp_group" => 'required',
        ]);

        $request = $request->toArray();
        $request['user_id'] = auth()->id();
        $request['team_id'] = auth()->user()->team()->id;

        $blog = Blog::create($request);

        return redirect("/blogs/{$blog->id}");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        $this->authorize('update', $blog);

        // Get fillable attributes/fields from model
        $modelStandardFields = $blog->fillableAttributesForForms();

        // Get blog groups from all team members
        $teamBlogGroups = BlogGroup::whereInTeam(auth()->user()->team()->id)
            ->get();

        // Add attributes/fields with multiple options
        $modelSelectableFields = [
            'blog_group_id' => [
                'fieldLabel' => 'Blog Group',
                'fieldOptions' => $teamBlogGroups
            ]
        ];

        $blog->route = 'blogs';

        return  view('models.show', [
            'model' => $blog,
            'modelStandardFields' => $modelStandardFields,
            'modelSelectableFields' => $modelSelectableFields
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $this->validate($request, [
            "name" => 'required|string|min:3|max:50',
            "blog_group_id" => 'required|int',
            "url" => 'required|url',
            "main_text" => 'required|string',
            "button_text" => 'required|string',
            "mailchimp_list" => 'required',
            "mailchimp_group" => 'required',
        ]);

        $blog->update($request->toArray());

        return redirect("/blogs/{$blog->id}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $this->authorize('delete', $blog);

        $blog->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Blog Deleted']);
        }

        return redirect('/blogs');
    }
}
