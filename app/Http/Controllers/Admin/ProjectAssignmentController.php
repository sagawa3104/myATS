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
    public function projectIndex(Request $request, Project $project)
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
    public function assignUser(Request $request, Project $project)
    {
        //
        $data = $request->all();
        $col = collect($data['assignments'])->flatten()->toArray();

        $project->members()->sync($col);

        return redirect(route('admin.project.index'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userIndex(Request $request, User $user)
    {
        //
        $user = User::find($user->id);

        $projects = $user->projects;

        $projectlist = Project::selectList();
        return view('admin.user.assignment.form', [
            'project' => $user,
            'projects' => $projects,
            'projectlist' => $projectlist,
            'formOptions' => [
                'route' => ['admin.user.assignment.assign', [$user->id,]],
                'method' => 'post',
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assignProject(Request $request, User $user)
    {
        //
        $data = $request->all();
        $col = collect($data['assignments'])->flatten()->toArray();
        $projects = Project::whereIn('code', $col)->get()->pluck('id');

        $user->projects()->sync($projects);

        return redirect(route('admin.user.index'));
    }
}
