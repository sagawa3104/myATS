<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreWorkRecordRequest;
use App\Http\Requests\User\UpdateWorkRecordRequest;
use App\Models\Project;
use App\Models\User;
use App\Models\WorkRecord;
use App\Models\WorkRecordDetail;
use App\Utils\StrtotimeConverter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        //
        $workrecords = WorkRecord::where('user_id', $user->id)->orderBy('workday', 'desc')->paginate(20);

        return view('user.workrecord.index', [
            'user' => $user,
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
        $workrecord = new WorkRecord([
            'workday' => $data['workday'],
            'attended_at' => '10:00',
            'left_at' => '19:00',
        ]);
        $porjects = Project::selectList();
        return view('user.workrecord.form', [
            'user' => $user,
            'workrecord' => $workrecord,
            'projects' => $porjects,
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

        $workRecord = new WorkRecord($data);
        $workRecord->user_id = $user->id;

        $workRecordDetails = $this->setWorkRecordDetails($data['workRecordDetails']);

        $workRecord->validate();

        DB::beginTransaction();
        try {
            $workRecord->save();

            $workRecord->workRecordDetails()->saveMany($workRecordDetails);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput();
        }

        DB::commit();

        return redirect(route('user.workrecord.index', [$user->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, WorkRecord $workrecord)
    {
        $porjects = Project::selectList();
        return view('user.workrecord.show', [
            'user' => $user,
            'workrecord' => $workrecord,
            'projects' => $porjects,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, WorkRecord $workrecord)
    {
        //
        $porjects = Project::selectList();
        $workrecord->fill([
            'attended_at' => StrtotimeConverter::convertTimeFormat($workrecord->attended_at),
            'left_at' => StrtotimeConverter::convertTimeFormat($workrecord->left_at),
        ]);
        return view('user.workrecord.form', [
            'user' => $user,
            'workrecord' => $workrecord,
            'projects' => $porjects,
            'formOptions' => [
                'route' => ['user.workrecord.update', [$user->id, $workrecord->id]],
                'method' => 'put',
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWorkRecordRequest $request, User $user, WorkRecord $workrecord)
    {
        //
        $data = $request->all();
        $workrecord->fill($data);
        $oldWorkRecordDetails = $workrecord->workRecordDetails();

        $workRecordDetails = $this->setWorkRecordDetails($data['workRecordDetails']);
        $workrecord->validate();

        DB::beginTransaction();
        try {
            $oldWorkRecordDetails->delete();
            $workrecord->save();

            $workrecord->workRecordDetails()->saveMany($workRecordDetails);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput();
        }
        DB::commit();

        return redirect(route('user.workrecord.index', [$user->id]));
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


    /**
     * 勤怠明細データの設定
     * 
     * @param array $data
     * @return array App\Models\WorkRecordDetail
     */
    private function setWorkRecordDetails($data)
    {
        $workRecordDetails = array();
        $errorValidator = null;
        $errors = [];
        foreach ($data as $index => $workRecordDetailData) {
            if (is_null($workRecordDetailData['project_code'])) continue;
            $workRecordDetail = new WorkRecordDetail([
                'project_id' => Project::where('code', $workRecordDetailData['project_code'])->first()->id,
                'work_time' => StrtotimeConverter::strHourToIntMinute($workRecordDetailData['work_time']),
                'content' => $workRecordDetailData['content'],
            ]);
            try {
                $workRecordDetail->validate();
                array_push($workRecordDetails, $workRecordDetail);
            } catch (ValidationException $e) {
                if (is_null($errorValidator)) {
                    $errorValidator = $e->validator;
                }
                $validationErrors = $e->errors();
                foreach ($validationErrors as $key => $value) {
                    $errors += ['workRecordDetail.' . $index . '.' . $key => $value];
                }
            }
        }

        if (!is_null($errorValidator)) {
            $errorValidator->errors()->merge($errors);
            throw new ValidationException($errorValidator);
        }

        return $workRecordDetails;
    }
}
