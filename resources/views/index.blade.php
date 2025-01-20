@extends('layouts.app')

@section('title', 'Capitals Quiz')

@section('content')
    <div id="token" class="d-none">@csrf</div>
    <div id="app"></div>
@endsection
