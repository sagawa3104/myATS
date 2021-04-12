<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreWorkRecordRequest;
use App\Http\Requests\User\UpdateWorkRecordRequest;
use App\Models\Project;
use App\Models\User;
use App\Models\WorkRecord;
use App\Models\WorkRecordDetail;
use App\Utils\Consts\ExecResult;
use App\Utils\StrtotimeConverter;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkRecordController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(WorkRecord::class, 'workrecord');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        //
        $data = $request->all();
        $baseday = isset($data['target']) ? Carbon::parse($data['target']) : Carbon::now();
        $st = $baseday->copy();
        $st->firstOfMonth()->subDays($st->dayOfWeek);
        $ed = $baseday->copy();
        $ed->lastOfMonth()->addDays(Carbon::SATURDAY - $ed->dayOfWeek);

        $period = Carbon::instance($st)->daysUntil($ed);
        $calender = collect();
        foreach ($period as $day) {
            $calender->push($day);
        }

        $workrecords = WorkRecord::where('user_id', $user->id)->orderBy('workday', 'desc')->paginate(20);
        $test = WorkRecord::where('user_id', $user->id)->whereBetween('workday', [$st->toDateString(), $ed->toDateString()])->orderBy('workday', 'desc')->get();

        $calender = $calender->map(function ($date) use ($test) {
            $res = $test->search(
                function ($wr) use ($date) {
                    return  $wr->workday == $date->toDateString();
                }
            );

            return ['date' => $date, 'workRecord' => $res !== false ? $test->get($res) : null];
        });

        return view('user.workrecord.index', [
            'user' => $user,
            'workrecords' => $workrecords,
            'baseday' => $baseday,
            'calender' => $calender,
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

        $porjects = $user->assinedProjectList();

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
        $status = ExecResult::FAILURE;

        DB::beginTransaction();
        try {
            $workRecord->save();
            $status = ExecResult::SUCCESS;
            $message = '登録が完了しました';
            $workRecord->workRecordDetails()->saveMany($workRecordDetails);
        } catch (Exception $e) {
            DB::rollBack();
            $status = ExecResult::FAILURE;
            $message = $e->getMessage();
            return back()->withInput();
        }

        DB::commit();

        return redirect(route('user.workrecord.index', [$user->id]))->with($status, $message);;
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
        $status = ExecResult::FAILURE;
        try {
            $oldWorkRecordDetails->delete();
            $workrecord->save();
            $status = ExecResult::SUCCESS;
            $message = '登録が完了しました';

            $workrecord->workRecordDetails()->saveMany($workRecordDetails);
        } catch (Exception $e) {
            DB::rollBack();
            $status = ExecResult::FAILURE;
            $message = $e->getMessage();
            return back()->withInput();
        }
        DB::commit();

        return redirect(route('user.workrecord.index', [$user->id]))->with($status, $message);;
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
            $project = Project::where('code', $workRecordDetailData['project_code'])->first();
            $workRecordDetail = new WorkRecordDetail([
                'project_id' => $project->id,
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
