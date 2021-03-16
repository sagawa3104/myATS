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
                                {{ Form::date('workday', $workday, []) }}
                                @error('workday')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="attended_at">勤務開始時間</label>
                                {{ Form::time('attended_at', '10:00') }}
                                @error('attended_at')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                                <label for="left_at">勤務終了時間</label>
                                {{ Form::time('left_at', '19:00') }}
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
                                    @empty(old('project_id'))
                                    <tr>
                                        <td>
                                            {{ Form::select('project_id[]', $projects, null, ['class' => 'form-control', 'id' => 'project_id[]' ]) }}
                                        </td>
                                        <td>
                                            {{ Form::time('work_time[]', null, ['class' => 'form-control', 'id' => 'work_time[]' ]) }}
                                        </td>
                                        <td>
                                            {{ Form::text('content[]', null, ['class' => 'form-control', 'id' => 'content[]' ]) }}
                                        </td>
                                        <td>
                                            {{ Form::button('行削除', ['class' => 'btn btn-secondary mx-1 my-1', 'id' => 'deleteRow']) }}
                                        </td>
                                    </tr>
                                    @else
                                        @for ($i=0; $i< count(old('project_id')) ;$i++)
                                            <tr>
                                                <td>
                                                    {{ Form::select('project_id[]', $projects, null, ['class' => 'form-control', 'id' => 'project_id[]' ]) }}
                                                    @error('project_id.'.$i)
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </td>
                                                <td>
                                                    {{ Form::time('work_time[]', null, ['class' => 'form-control', 'id' => 'work_time[]' ]) }}
                                                    @error('work_time.'.$i)
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </td>
                                                <td>
                                                    {{ Form::text('content[]', null, ['class' => 'form-control', 'id' => 'content[]' ]) }}
                                                    @error('content.'.$i)
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </td>
                                                <td>
                                                    {{ Form::button('行削除', ['class' => 'btn btn-secondary mx-1 my-1', 'id' => 'deleteRow']) }}
                                                </td>
                                            </tr>
                                        @endfor
                                    @endempty
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
                        @isset($workrecord->id)
                            {{ Form::button('削除', ['class' => 'btn btn-secondary', 'data-toggle' => 'modal', 'data-target' => '#deleteModal']) }}
                        @endisset
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
    const addDetail = document.querySelector('#addDetail');
    addDetail.addEventListener('click', function(){
        $('#detailTable tbody tr:last-child').clone(true).appendTo('#detailTable tbody');

        $("#detailTable tbody tr:last-child input").val("");
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