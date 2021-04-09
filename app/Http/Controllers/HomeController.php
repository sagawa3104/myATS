<?php

namespace App\Http\Controllers;

use App\Models\WorkRecord;
use App\Utils\StrtotimeConverter;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::now();

        $workrecords = WorkRecord::where('user_id', $user->id)->whereYear('workday', $today->format('Y'))->whereMonth('workday', $today->format('m'))->get();
        $wt = $workrecords->sum('working_time');
        $ot = $workrecords->sum('overtime');
        $wt_per_project = $workrecords
            //WorkRecordDetailごとの情報を得て
            ->map(function ($workrecord) {
                $details = $workrecord->workRecordDetails;
                return $details
                    //workRecordDetailの情報をプロジェクトコードと作業時間のみとした配列を返す
                    ->map(function ($detail) {
                        return ['code' => $detail->project->code . ' ' . $detail->project->name, 'work_time' => $detail->work_time];
                    });
            })
            //workRecordごとだったので次数を1つ落とし、プロジェクトコードでグルーピングし
            ->flatten(1)->groupBy('code')
            //作業時間を合計する
            ->map(function ($b) {
                return $b->sum('work_time');
            })
            ->map(function ($c) {
                return StrtotimeConverter::intMinuteToStrHour($c);
            });



        return view('home', [
            'user' => $user,
            'today' => $today->format('Y-m-d'),
            'wdcnt' => $workrecords->count(),
            'wt' => StrtotimeConverter::intMinuteToStrHour($wt),
            'ot' => StrtotimeConverter::intMinuteToStrHour($ot),
            'wt_per_project' => $wt_per_project,
        ]);
    }

    public function calender(Request $request)
    {
        $baseday = Carbon::now();
        // $st = $day->firstOfMonth()->subDays($day->dayOfWeek)->copy();
        // $ed = $day->lastOfMonth()->addDays(Carbon::SATURDAY - $day->dayOfWeek)->copy();
        $st = $baseday->copy();
        $st->firstOfMonth()->subDays($st->dayOfWeek);
        $ed = $baseday->copy();
        $ed->lastOfMonth()->addDays(Carbon::SATURDAY - $ed->dayOfWeek);

        $period = Carbon::instance($st)->daysUntil($ed);
        $calender = collect();
        foreach ($period as $day) {
            $calender->push($day);
        }

        return view('calender', [
            'baseday' => $baseday,
            'calender' => $calender,
        ]);
    }
}
