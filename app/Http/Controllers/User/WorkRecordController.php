<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\WorkRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $workrecords = WorkRecord::where('user_id', Auth::user()->id)->orderBy('workday', 'desc')->paginate(20);

        return view('user.workrecord.index', [
            'user' => Auth::user(),
            'workrecords' => $workrecords,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, User $user)
    {
        //
        $data = $request->all();
        $workrecord = new WorkRecord();
        $porjects = Project::selectList();
        return view('user.workrecord.form', [
            'user' => $user,
            'workrecord' => $workrecord,
            'projects' => $porjects,
            'workday' => $data['workday'],
            'formOptions' => [
                'route' => ['user.workrecord.store', [$user->id,]],
                'method' => 'post',
            ],
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
