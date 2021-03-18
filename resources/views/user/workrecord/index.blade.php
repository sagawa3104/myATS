@extends('adminlte::page')

@section('title', 'myATS')

@section('content_header')
    <h1 class="m-0 text-dark">勤怠入力</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @include('layouts.alert')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0 mt-2">一覧</h3>
                    <div class="text-right">
                        {{ Form::open([
                            'url' => route('user.workrecord.create', $user->id),
                            'method' => 'get',
                        ])}}
                            {{ Form::date('workday', null, ['class' => 'mx-2 mt-1']) }}
                            {{ Form::submit('登録', ['class' => 'btn btn-primary mb-3']) }}
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <tr>
                            <th class="text-nowrap">勤務日</th>
                            <th class="text-nowrap">勤務時間</th>
                            <th class="text-nowrap">休憩時間</th>
                            <th class="text-nowrap">時間外労働時間</th>
                            <th></th>
                        </tr>
                    @foreach ($workrecords as $workrecord)
                        <tr>
                            <td>{{ $workrecord->workday}}</td>
                            <td>{{ $workrecord->intWorkingTimeToStrHour() }}</td>
                            <td>{{ $workrecord->intBreakTimeToStrHour() }}</td>
                            <td>{{ $workrecord->intOverTimeToStrHour() }}</td>
                            <td>
                                {{ Html::link(route('user.workrecord.edit', [$user->id, $workrecord->id]), '編集', ['class' => 'btn btn-sm btn-primary']) }}
                                {{ Html::link(route('user.workrecord.show', [$user->id, $workrecord->id]), '確認', ['class' => 'btn btn-sm btn-primary']) }}
                            </td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop