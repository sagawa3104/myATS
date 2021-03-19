@extends('adminlte::page')

@section('title', 'myATS')

@section('content_header')
    <h1 class="m-0 text-dark">ユーザー管理</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @include('layouts.alert')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0 mt-2">一覧</h3>
                    <div class="text-right">{{ Html::link(route('admin.user.create'), '登録', ['class' => 'btn btn-primary float-right']) }}</div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <tr>
                            <th class="text-nowrap">ID</th>
                            <th class="text-nowrap">ユーザー名</th>
                            <th class="text-nowrap">メールアドレス</th>
                            <th class="text-nowrap">管理者権限</th>
                        </tr>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id}}</td>
                            <td>{{ $user->name}}</td>
                            <td>{{ $user->email}}</td>
                            <td>{{ $user->getStrIsAdmin()}}</td>
                            <td>
                                {{ Html::link(route('admin.user.edit', [$user->id]), '編集', ['class' => 'btn btn-sm btn-primary']) }}
                                {{ Html::link(route('admin.user.show', [$user->id]), '確認', ['class' => 'btn btn-sm btn-primary']) }}
                            </td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop