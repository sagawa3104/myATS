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
                  @if(isset($workrecord->id))
                  編集
                  @else
                  登録
                  @endif
                  </h3>
                </div>
                <div class="card-body">
                {{ Form::model($workrecord, $formOptions) }}
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h4 class="card-title mb-0">ヘッダ</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="workday">勤務日</label>
                                {{ Form::date('workday', null, ['class' => 'form-control', 'id' => 'workday']) }}
                                @error('workday')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="attended_at">勤務開始時間</label>
                                {{ Form::time('attended_at', null, ['class' => 'form-control', 'id' => 'attended_at']) }}
                                @error('attended_at')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                                <label for="left_at">勤務終了時間</label>
                                {{ Form::time('left_at', null, ['class' => 'form-control', 'id' => 'left_at']) }}
                                @error('left_at')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
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
                                        @empty(old('workRecordDetails'))
                                            @foreach($workrecord->workRecordDetails as $index => $workRecordDetail)
                                                <tr id={{"workRecordDetails_${index}"}} >
                                                    <td>
                                                        {{ Form::select("workRecordDetails[${index}][project_code]", $projects, $workRecordDetail->project->code, ['class' => 'form-control', 'id' => "${index}project_code" ]) }}
                                                    </td>
                                                    <td>
                                                        {{ Form::time("workRecordDetails[${index}][work_time]", $workRecordDetail->intWorkTimeToStrHour(), ['class' => 'form-control', 'id' => "${index}_work_time" ]) }}
                                                    </td>
                                                    <td>
                                                        {{ Form::text("workRecordDetails[${index}][content]", $workRecordDetail->content, ['class' => 'form-control', 'id' => "${index}_content" ]) }}
                                                    </td>
                                                    <td>
                                                        {{ Form::button('行削除', ['class' => 'btn btn-secondary mx-1 my-1', 'id' => 'deleteRow']) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            @for ($index=0; $index< count(old('workRecordDetails')) ;$index++)
                                                <tr id={{"workRecordDetails_${index}"}}>
                                                    <td>
                                                        {{ Form::select("workRecordDetails[${index}][project_code]", $projects, null, ['class' => 'form-control', 'id' => "${index}project_code" ]) }}
                                                        @error('workRecordDetail.'. $index . '.project_code')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::time("workRecordDetails[${index}][work_time]", null, ['class' => 'form-control', 'id' => "${index}_work_time" ]) }}
                                                        @error('workRecordDetail.'. $index . '.work_time')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::text("workRecordDetails[${index}][content]", null, ['class' => 'form-control', 'id' => "${index}_content" ]) }}
                                                        @error('workRecordDetail.'. $index . '.content')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::button('行削除', ['class' => 'btn btn-secondary mx-1 my-1', 'id' => 'deleteRow']) }}
                                                    </td>
                                                </tr>
                                            @endfor
                                        @endempty
                                    @else
                                        @empty(old('workRecordDetails'))
                                            <tr id={{"workRecordDetails_0"}}>
                                                <td>
                                                    {{ Form::select("workRecordDetails[0][project_code]", $projects, null, ['class' => 'form-control', 'id' => "project_code" ]) }}
                                                </td>
                                                <td>
                                                    {{ Form::time("workRecordDetails[0][work_time]", null, ['class' => 'form-control', 'id' => "0_work_time" ]) }}
                                                </td>
                                                <td>
                                                    {{ Form::text("workRecordDetails[0][content]", null, ['class' => 'form-control', 'id' => "0_content" ]) }}
                                                </td>
                                                <td>
                                                    {{ Form::button('行削除', ['class' => 'btn btn-secondary mx-1 my-1', 'id' => 'deleteRow']) }}
                                                </td>
                                            </tr>
                                        @else
                                            @for ($index=0; $index< count(old('workRecordDetails')) ;$index++)
                                                <tr id={{"workRecordDetails_${index}"}}>
                                                    <td>
                                                        {{ Form::select("workRecordDetails[${index}][project_code]", $projects, null, ['class' => 'form-control', 'id' => "${index}_project_code" ]) }}
                                                        @error('workRecordDetail.'. $index . '.project_code')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::time("workRecordDetails[${index}][work_time]", null, ['class' => 'form-control', 'id' => "${index}_work_time" ]) }}
                                                        @error('workRecordDetail.'. $index . '.work_time')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::text("workRecordDetails[${index}][content]", null, ['class' => 'form-control', 'id' => "${index}_content" ]) }}
                                                        @error('workRecordDetail.'. $index . '.content')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::button('行削除', ['class' => 'btn btn-secondary mx-1 my-1', 'id' => 'deleteRow']) }}
                                                    </td>
                                                </tr>
                                            @endfor
                                        @endempty
                                    @endif
                                </tbody>
                            </table>
                            {{ Form::button('明細追加', ['class' => 'btn btn-primary mx-1 my-1', 'id' => 'addDetail']) }}
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <div class="btn-group" role="group">
                        {{ Html::link(route('user.workrecord.index', $user->id), '戻る', ['class' => 'btn btn-secondary mr-2']) }}
                        {{ Form::submit('保存', ['class' => 'btn btn-primary mr-2']) }}
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            @isset($workrecord->id)
                @include('layouts.deleteModal', ['id' => $workrecord->id, 'url' => route('user.workrecord.destroy', [$user->id, $workrecord->id])])
            @endisset
        </div>
    </div>
@stop

@section('js')
<script>
    let rows =  $('#detailTable tbody').children().length;
    const WORKRECORDDETAILS ='workRecordDetails';
    const PROJECT_CODE = 'project_code';
    const WORK_TIME = 'work_time';
    const CONTENT = 'content';
    $(document).on('click', '#addDetail', function(){
        $('#detailTable tbody tr:last-child').clone(true).attr('id', 'workRecordDetails_'+rows).appendTo('#detailTable tbody');
        

        $('#detailTable tbody tr:last-child td [name^="workRecordDetails"]').each(function (index, elem){
            switch($(elem).attr('id').replace(/\d+_/, '')){
                case PROJECT_CODE:
                    $(elem).attr('id', rows+'_'+PROJECT_CODE);
                    $(elem).attr('name', WORKRECORDDETAILS+'['+rows+']'+'['+PROJECT_CODE+']')
                    console.log($(elem).attr('name'));
                break;
                case WORK_TIME:
                    $(elem).attr('id', rows+'_'+WORK_TIME);
                    $(elem).attr('name', WORKRECORDDETAILS+'['+rows+']'+'['+WORK_TIME+']')
                    console.log($(elem).attr('name'));
                    break;
                    case CONTENT:
                    $(elem).attr('id', rows+'_'+CONTENT);
                    $(elem).attr('name', WORKRECORDDETAILS+'['+rows+']'+'['+CONTENT+']')
                    console.log($(elem).attr('name'));
                break;
            }

        });

        $('#detailTable tbody tr:last-child td [name^="workRecordDetails"]').val("");
        rows++;
    });

    $(document).on('click', '#deleteRow', function(){
        const rowCount = $('#detailTable tbody').children().length;
        if(rowCount > 1){
            $(this).parents().parents('tr').remove();
        } 
    })

</script>
@stack('deleteModalJs')
@stop