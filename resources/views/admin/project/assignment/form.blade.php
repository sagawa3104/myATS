@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">プロジェクト管理</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-secondary">
                <div class="card-header">
                  <h3 class="card-title mb-0">
                      要員割当
                  </h3>
                </div>
                {{ Form::open($formOptions) }}
                <div class="card-body">
                    <table class="table table-hover" id='assignTable'>
                        <thead>
                            <tr>
                                <th>メンバー</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($members)
                            @foreach ($members as $index => $member)
                            <tr id={{"assignments_${index}"}}>
                                <td>
                                    {{ Form::select("assignments[$index][member_id]", $userlist, $member->id, ['class' => 'form-control', 'id' => "${index}_member_id" ]) }}
                                </td>
                                <td>
                                    {{ Form::button('行削除', ['class' => 'btn btn-secondary mx-1 my-1', 'id' => 'deleteRow']) }}
                                </td>
                            </tr>
                            @endforeach
                            @else    
                            <tr id={{"assignments_0"}}>
                                <td>
                                    {{ Form::select("assignments[0][member_id]", $userlist, null, ['class' => 'form-control', 'id' => "0_member_id" ]) }}
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
                    {{ Html::link(route('admin.project.index'), '戻る', ['class' => 'btn btn-secondary mr-2']) }}
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
    const MEMBER_ID = 'member_id';
    $(document).on('click', '#addDetail', function(){
        $('#assignTable tbody tr:last-child').clone(true).attr('id', 'assignments_'+rows).appendTo('#assignTable tbody');
        

        $('#assignTable tbody tr:last-child td [name^="assignments"]').each(function (index, elem){
            switch($(elem).attr('id').replace(/\d+_/, '')){
                case MEMBER_ID:
                    $(elem).attr('id', rows+'_'+MEMBER_ID);
                    $(elem).attr('name', ASSIGNMENTS+'['+rows+']'+'['+MEMBER_ID+']')
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