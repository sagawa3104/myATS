<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreWorkRecordRequest;
use App\Models\Project;
use App\Models\User;
use App\Models\WorkRecord;
use App\Models\WorkRecordDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function store(StoreWorkRecordRequest $request, User $user)
    {
        //
        $data = $request->all();

        $workrecord = new WorkRecord([
            'user_id' => $user->id,
            'workday' => $data['workday'],
            'attended_at' => $data['attended_at'],
            'left_at' => $data['left_at'],
            'working_time' => $data['working_time'],
            'break_time' => $data['break_time'],
            'overtime' => $data['overtime'],
        ]);

        $workRecordDetails = $this->setWorkRecordDetails($data);

        DB::beginTransaction();
        try {
            $workrecord->save();

            $workrecord->workRecordDetails()->saveMany($workRecordDetails);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput();
        }

        DB::commit();

        return redirect(route('user.workrecord.index'));
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


    private function setWorkRecordDetails($data)
    {

        $workRecordDetails = [];
        for ($i = 0; $i < count($data['project_id']); $i++) {
            $workRecordDetail = new WorkRecordDetail([
                'project_id' => $data['project_id'][$i],
                'work_time' => $data['work_time'][$i],
                'content' => $data['content'][$i],
            ]);
            $workRecordDetails += array($workRecordDetail);
        }

        return $workRecordDetails;
    }
}
