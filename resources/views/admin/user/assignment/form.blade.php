@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">ユーザー管理</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-secondary">
                <div class="card-header">
                  <h3 class="card-title mb-0">
                      案件割当
                  </h3>
                </div>
                {{ Form::open($formOptions) }}
                <div class="card-body">
                    <table class="table table-hover" id='assignTable'>
                        <thead>
                            <tr>
                                <th>プロジェクト</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($projects)
                            @foreach ($projects as $index => $project)
                            <tr id={{"assignments_${index}"}}>
                                <td>
                                    {{ Form::select("assignments[$index][project_code]", $projectlist, $project->code, ['class' => 'form-control', 'id' => "${index}_project_code" ]) }}
                                </td>
                                <td>
                                    {{ Form::button('行削除', ['class' => 'btn btn-secondary mx-1 my-1', 'id' => 'deleteRow']) }}
                                </td>
                            </tr>
                            @endforeach
                            @else    
                            <tr id={{"assignments_0"}}>
                                <td>
                                    {{ Form::select("assignments[0][project_code]", $projectlist, null, ['class' => 'form-control', 'id' => "0_project_code" ]) }}
                                </td>
                                <td>
                                    {{ Form::button('行削除', ['class' => 'btn btn-secondary mx-1 my-1', 'id' => 'deleteRow']) }}
                                </td>
                            </tr>
                            @endisset
                        </tbody>
                    </table> 
                    {{ Form::button('明細追加', ['class' => 'btn btn-primary mx-1 my-1', 'id' => 'addDetail']) }}
                </div>
                <div class="card-footer">
                    {{ Html::link(route('admin.user.index'), '戻る', ['class' => 'btn btn-secondary mr-2']) }}
                    {{ Form::submit('保存', ['class' => 'btn btn-primary mr-2']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    let rows =  $('#assignTable tbody').children().length;
    const ASSIGNMENTS ='assignments';
    const PROJECT_CODE = 'project_code';
    $(document).on('click', '#addDetail', function(){
        $('#assignTable tbody tr:last-child').clone(true).attr('id', 'assignments_'+rows).appendTo('#assignTable tbody');
        

        $('#assignTable tbody tr:last-child td [name^="assignments"]').each(function (index, elem){
            switch($(elem).attr('id').replace(/\d+_/, '')){
                case PROJECT_CODE:
                    $(elem).attr('id', rows+'_'+PROJECT_CODE);
                    $(elem).attr('name', ASSIGNMENTS+'['+rows+']'+'['+PROJECT_CODE+']')
                    console.log($(elem).attr('name'));
                break;
            }

        });
        $('#assignTable tbody tr:last-child td [name^="assignments"]').val("");
        rows++;
    });

    $(document).on('click', '#deleteRow', function(){
        const rowCount = $('#assignTable tbody').children().length;
        if(rowCount > 1){
            $(this).parents().parents('tr').remove();
        } 
    })

</script>
@stack('deleteModalJs')
@stop