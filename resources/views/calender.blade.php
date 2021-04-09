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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                @foreach (['日', '月', '火', '水', '木', '金', '土'] as $dow)
                                    <th>{{$dow}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($calender as $date)
                                @if ($date->isSunday())
                                    <tr>
                                @endif
                                    @if ($date->month <> $baseday->month )
                                    <td class="bg-light">
                                    @else
                                    <td>
                                    @endif
                                    <a href="#" class="h6">{{$date->format('d')}}</a>
                                        <div class="">
                                            <span>作業時間：xx h</span>
                                        </div>
                                    </td>
                                @if($date->isSaturday())
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
