@extends('layouts.app')

@section('title', 'Ролі')

@if ($canCreate)
    @section('h-btn-title', 'Додати роль')
    @section('h-btn-icon', 'add')
    @section('h-btn-href', '/role/create')
    @section('h-btn-class', 'md-raised')
@endif

@section('content')

    <roles i-user="{{ json_encode($me) }}"
           in-roles="{{ json_encode($roles) }}"
    ></roles>

@endsection
