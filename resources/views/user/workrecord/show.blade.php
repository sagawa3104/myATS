@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">勤怠管理</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-secondary">
                <div class="card-header">
                  <h3 class="card-title mb-0">
                      確認
                  </h3>
                </div>
                <div class="card-body">
                {{ Form::model($workrecord) }}
                <div class="card card-secondary">
                    <div class="card-header">
                        <h4 class="card-title mb-0">ヘッダ</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="workday">勤務日</label>
                            {{ Form::date('workday', null, ['class' => 'form-control', 'id' => 'workday', 'readonly' => 'true' ]) }}
                         </div>
                        <div class="form-group">
                            <label for="attended_at">勤務開始時間</label>
                            {{ Form::time('attended_at', null, ['class' => 'form-control', 'id' => 'attended_at', 'readonly' => 'true' ]) }}
                            <label for="left_at">勤務終了時間</label>
                            {{ Form::time('left_at', null, ['class' => 'form-control', 'id' => 'left_at', 'readonly' => 'true' ]) }}
                        </div>
                    </div>
                </div>
                <div class="card card-secondary">
                    <div class="card-header">
                        <h4 class="card-title mb-0">明細</h4>
                    </div>
                    <div class="form-body">
                        <table class="table table-hover" id='detailTable'>
                            <thead>
                                <tr>
                                    <th>プロジェクト</th>
                                    <th>
                                        稼働時間
                                        @error('sum_work_time')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </th>
                                    <th>作業内容</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($workrecord->id))
                                    @foreach($workrecord->workRecordDetails as $workRecordDetail)
                                        <tr>
                                            <td>
                                                {{ Form::select('project_id[]', $workRecordDetail->project->selectedItem(), $workRecordDetail->project_id, ['class' => 'form-control', 'id' => 'project_id[]', 'readonly' => 'true' ]) }}
                                            </td>
                                            <td>
                                                {{ Form::time('work_time[]', $workRecordDetail->intWorkTimeToStrHour(), ['class' => 'form-control', 'id' => 'work_time[]', 'readonly' => 'true' ]) }}
                                            </td>
                                            <td>
                                                {{ Form::text('content[]', $workRecordDetail->content, ['class' => 'form-control', 'id' => 'content[]', 'readonly' => 'true' ]) }}
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="form-footer">
                        <div class="btn-group" role="group">
                            {{ Html::link(route('user.workrecord.index', $user->id), '戻る', ['class' => 'btn btn-secondary mr-2']) }}
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
