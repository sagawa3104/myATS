<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Models\Project;
use App\Utils\Consts\ExecResult;
use Exception;

class ProjectController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::orderBy('id', 'desc')->paginate(20);

        return view('admin.project.index', [
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $project = new Project();
        return view('admin.project.form', [
            'project' => $project,
            'formOptions' => [
                'route' => ['admin.project.store',],
                'method' => 'post',
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Admin\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->all();
        $status = ExecResult::FAILURE;
        $project = new Project([
            'code' => $data['code'],
            'name' => $data['name'],
        ]);
        $project->validate();
        try {
            $project->save();
            $status = ExecResult::SUCCESS;
            $message = '登録が完了しました';
        } catch (Exception $e) {
            $status = ExecResult::FAILURE;
            $message = $e->getMessage();
        }

        return redirect(route('admin.project.index'))->with($status, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('admin.project.show', [
            'project' => $project,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view('admin.project.form', [
            'project' => $project,
            'formOptions' => [
                'route' => ['admin.project.update', [$project->id]],
                'method' => 'put',
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Admin\UpdateProjectRequest;  $request
     * @param  App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $status = ExecResult::FAILURE;
        $data = $request->all();
        $project->fill([
            'code' => $data['code'],
            'name' => $data['name'],
        ]);
        $project->validate();

        try {
            $project->save();
            $status = ExecResult::SUCCESS;
            $message = '更新しました';
        } catch (Exception $e) {
            $status = ExecResult::FAILURE;
            $message = $e->getMessage();
        }
        return redirect(route('admin.project.index'))->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $status = ExecResult::FAILURE;
        try {
            $project->delete();
            $status = ExecResult::SUCCESS;
            $message = '削除しました';
        } catch (Exception $e) {
            $status = ExecResult::FAILURE;
            $message = $e->getMessage();
        }
        return redirect(route('admin.project.index'))->with($status, $message);
    }
}
