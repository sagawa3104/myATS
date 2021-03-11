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
                  @if(isset($user->id))
                  編集
                  @else
                  登録
                  @endif
                  </h3>
            </div>
            <div class="card-body">
              {{ Form::model($user, $formOptions) }}
                  <div class="form-group">
                      <label for="name">ユーザー名</label>
                      {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name' ]) }}
                      @error('name')
                          <p class="text-danger">{{ $message }}</p>
                      @enderror
                  </div>
                  <div class="form-group">
                      <label for="email">メールアドレス</label>
                      {{ Form::text('email', null, ['class' => 'form-control', 'id' => 'email' ]) }}
                      @error('email')
                          <p class="text-danger">{{ $message }}</p>
                      @enderror
                  </div>
                  <div class="form-group">
                      <label for="password">パスワード</label>
                      {{ Form::password('password', ['class' => 'form-control', 'id' => 'password']) }}
                      @error('password')
                          <p class="text-danger">{{ $message }}</p>
                      @enderror
                  </div>
              </div>
                <div class="card-footer">
                  <div class="btn-group" role="group">
                      {{ Html::link(route('user.index'), '戻る', ['class' => 'btn btn-secondary mr-2']) }}
                      {{ Form::submit('保存', ['class' => 'btn btn-primary mr-2']) }}
                      @isset($user->id)
                      {{ Form::button('削除', ['class' => 'btn btn-secondary', 'data-toggle' => 'modal', 'data-target' => '#deleteModal']) }}
                      @endisset
                  </div>
                </div>
                {{ Form::close() }}
              </div>
        </div>
    </div>
@stop
