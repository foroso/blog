@extends('layouts.default')

@section('content')
    <div class="jumbotron">
        <h1>Laravel 测试</h1>
        <p class="lead">
            你现在所看到的是 <a href="">我的演示</a> 项目主页。
        </p>
        <p>
            一切，将从这里开始。
        </p>
        <p>
            <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">现在注册</a>
        </p>
    </div>
@stop