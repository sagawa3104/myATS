@extends('adminlte::page')

@section('title', 'myATS')

@section('content_header')
    <h1 class="m-0 text-dark">ユーザー管理</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0 mt-2">一覧</h3>
                    <div class="text-right">登録</div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <tr>
                            <th class="text-nowrap">col1</th>
                            <th class="text-nowrap">col2</th>
                            <th class="text-nowrap">col3</th>
                            <th class="text-nowrap">col4</th>
                            <th class="text-nowrap">col5</th>
                            <th class="text-nowrap">col6</th>
                            <th class="text-nowrap">col7</th>
                        </tr>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id}}</td>
                            <td>{{ $user->name}}</td>
                            <td>{{ $user->email}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop