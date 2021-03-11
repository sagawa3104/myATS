@extends('adminlte::page')

@section('title', 'myATS')

@section('content_header')
    <h1 class="m-0 text-dark">プロジェクト管理</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @include('layouts.alert')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0 mt-2">一覧</h3>
                    <div class="text-right">{{ Html::link(route('project.create'), '登録', ['class' => 'btn btn-primary float-right']) }}</div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <tr>
                            <th class="text-nowrap">col1</th>
                            <th class="text-nowrap">col2</th>
                            <th class="text-nowrap">col3</th>
                            <th class="text-nowrap">col4</th>
                        </tr>
                    @foreach ($projects as $project)
                        <tr>
                            <td>{{ $project->id}}</td>
                            <td>{{ $project->name}}</td>
                            <td>{{ $project->code}}</td>
                            <td>
                                {{ Html::link(route('project.edit', [$project->id]), '編集', ['class' => 'btn btn-sm btn-primary']) }}
                                {{ Html::link(route('project.show', [$project->id]), '確認', ['class' => 'btn btn-sm btn-primary']) }}
                            </td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop