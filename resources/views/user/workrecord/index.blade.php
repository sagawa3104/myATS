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
                    <p class="card-title mt-3">{{$baseday->format('Y年m月')}}</p>
                    <div class="float-right btn-group">
                        <a href="{{route('user.workrecord.index', ['user' => $user->id, 'target' => $baseday->copy()->subMonth()->format('Y-m')])}}" class="btn btn-primary mr-2">前月</a>
                        <a href="{{route('user.workrecord.index', ['user' => $user->id])}}" class="btn btn-primary mr-2">今日</a>
                        <a href="{{route('user.workrecord.index', ['user' => $user->id, 'target' => $baseday->copy()->addMonth()->format('Y-m')])}}" class="btn btn-primary">次月</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <col width="10%">
                        <col span="5">
                        <col width="10%">
                        <thead>
                            <tr>
                                @foreach (['日', '月', '火', '水', '木', '金', '土'] as $dow)
                                    <th>{{$dow}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($calender as $date)
                                @if ($date['date']->isSunday())
                                    <tr height="120px">
                                @endif
                                    @if ($date['date']->month <> $baseday->month )
                                    <td class="bg-light">
                                    @else
                                    <td>
                                    @endif
                                        @if (isset($date['workRecord']))
                                            <a href="{{route('user.workrecord.edit', [$user->id, $date['workRecord']->id])}}" class="d-flex">
                                                {{$date['date']->format('d')}}
                                            </a>
                                            <div class="d-flex">
                                                <span>{{$date['workRecord']->attended_at. ' ~ '.$date['workRecord']->left_at}}</span>
                                            </div>
                                        @else
                                            {{ Html::link(route('user.workrecord.create', ['user' => $user->id, 'workday' => $date['date']->format('Y-m-d')]), $date['date']->format('d'), ['class' => 'd-flex']) }}
                                        @endif
                                    </td>
                                @if($date['date']->isSaturday())
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop