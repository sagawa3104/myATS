<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Collective\Html\FormBuilder;
use Illuminate\Http\Request;

class ProjectAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Project $project)
    {
        //
        $project = Project::find($project->id);

        $members = $project->members;

        $userlist = User::selectList();
        return view('admin.project.assignment.form', [
            'project' => $project,
            'members' => $members,
            'userlist' => $userlist,
            'formOptions' => [
                'route' => ['admin.project.assignment.assign', [$project->id,]],
                'method' => 'post',
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assign(Request $request, Project $project)
    {
        //
        $data = $request->all();
        $col = collect($data['assignments'])->flatten()->toArray();

        $project->members()->sync($col);

        return redirect(route('admin.project.index'));
    }
}
