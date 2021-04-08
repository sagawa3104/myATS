@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="h3">{{ $today }}</p>
                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title mt-2">今月の稼働実績サマリー</h3>
                                    <div class="float-right">
                                        <a href="#" class="btn btn-primary">前月</a>
                                        <a href="#" class="btn btn-primary">次月</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"><div class="float-left">稼働日数：</div><div class="float-right "><span>{{$wdcnt}}</span>日</div></li>
                                        <li class="list-group-item"><div class="float-left">勤務時間合計：</div><div class="float-right "><span>{{$wt}}</span></div></li>
                                        <li class="list-group-item"><div class="float-left">時間外労働時間合計：</div><div class="float-right "><span>{{$ot}}</span></div></li>
                                        <li class="list-group-item ml-0">プロジェクトごとの明細：
                                            <ul class="list-group-flush">
                                                @foreach ($wt_per_project as $key => $value)
                                                    <li class="list-group-item">
                                                        {{$key}}:
                                                        <div class="float-right "><span>{{$value}}</span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-footer">
                                        <a href="#" class="btn btn-primary">勤務表出力</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
