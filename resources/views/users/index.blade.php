@extends('layouts.app')

@section('title', 'Користувачі')

@if ($canCreate)
    @section('h-btn-title', 'Додати працівника')
    @section('h-btn-icon', 'add')
    @section('h-btn-href', '/users/create')
    @section('h-btn-class', 'md-raised')
@endif

@section('content')

    <users i-user="{{ json_encode($me) }}"
           in-users="{{ json_encode($users) }}"
           in-roles="{{ json_encode($roles) }}"
           in-jobs="{{ json_encode($jobs) }}"
    ></users>

@endsection
