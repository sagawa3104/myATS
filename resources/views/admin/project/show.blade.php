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
                      確認
                  </h3>
                </div>
                <div class="card-body">
                {{ Form::model($project) }}
                    <div class="form-group">
                        <label for="name">プロジェクト名</label>
                        {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'readonly' => 'true']) }}
                    </div>
                    <div class="form-group">
                        <label for="code">プロジェクトコード</label>
                        {{ Form::text('code', null, ['class' => 'form-control', 'id' => 'code', 'readonly' => 'true']) }}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group" role="group">
                        {{ Html::link(route('admin.project.index'), '戻る', ['class' => 'btn btn-secondary mr-2']) }}
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
