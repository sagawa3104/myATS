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
                      確認
                  </h3>
                </div>
                <div class="card-body">
                {{ Form::model($user) }}
                    <div class="form-group">
                        <label for="name">ユーザー名</label>
                        {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'readonly' => 'true']) }}
                    </div>
                    <div class="form-group">
                        <label for="email">メールアドレス</label>
                        {{ Form::text('email', null, ['class' => 'form-control', 'id' => 'email', 'readonly' => 'true']) }}
                    </div>
                    <div class="form-group">
                        <label for="password">パスワード</label>
                        {{ Form::password('password', ['class' => 'form-control', 'id' => 'password', 'readonly' => 'true']) }}
                    </div>
                    <label for="is_admin">管理者権限</label>
                    <div class="form-check mb-3 pb-3">
                        {{ Form::checkbox('is_admin', 1, null, ['class' => 'form-check-input', 'id' => 'is_admin', 'disabled']) }}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group" role="group">
                        {{ Html::link(route('admin.user.index'), '戻る', ['class' => 'btn btn-secondary mr-2']) }}
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
