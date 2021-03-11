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
                        {{ Form::date('workday', null, ['class' => 'mx-2']) }}
                        {{ Html::link(route('project.create'), '登録', ['class' => 'btn btn-primary float-right']) }}
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <tr>
                            <th class="text-nowrap">col1</th>
                            <th class="text-nowrap">col2</th>
                            <th class="text-nowrap">col3</th>
                            <th class="text-nowrap">col4</th>
                        </tr>
                    @foreach ($workrecords as $workrecord)
                        <tr>
                            <td>{{ $workrecord->id}}</td>
                            <td>{{ $workrecord->name}}</td>
                            <td>{{ $workrecord->code}}</td>
                            <td>
                                {{ Html::link(route('workrecord.edit', [$workrecord->id]), '編集', ['class' => 'btn btn-sm btn-primary']) }}
                                {{ Html::link(route('workrecord.show', [$workrecord->id]), '確認', ['class' => 'btn btn-sm btn-primary']) }}
                            </td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop