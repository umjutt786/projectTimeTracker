<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;


class AdminProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $freelancers = User::where('role', 'freelancer')->pluck('name', 'id');
        return view('projects.create', compact('freelancers'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'freelancer_id' => 'nullable|integer|exists:users,id',
            'status' => 'required|in:open,in_progress,completed'
        ]);
    
        // Automatically set the client_id to the current authenticated user's ID
        $validatedData['client_id'] = auth()->user()->id;
    
        // Create the new project
        Project::create($validatedData);
    
        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['client', 'freelancer', 'workLogs.screenshots']);
        return view('projects.show', compact('project'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
{
    // Fetch users where the role is 'freelancer'
    $freelancers = User::where('role', 'freelancer')->pluck('name', 'id');

    // Pass both the project and freelancers to the view
    return view('projects.edit', compact('project', 'freelancers'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        // Validate and update the project
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'client_id' => 'required|integer',
            'freelancer_id' => 'nullable|integer',
            'status' => 'required|in:open,in_progress,completed'
        ]);

        $project->update($validatedData);
        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
