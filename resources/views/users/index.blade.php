@extends('layouts.app')

@section('title')
    Користувачі
@endsection

@section('content')

    <users in-users="{{ json_encode($users) }}"
           in-roles="{{ json_encode($roles) }}"
           count="{{ empty($_GET['count']) ? 10 : $_GET['count'] }}"
           role="{{ empty($_GET['role']) ? '' : $_GET['role'] }}"
           active="{{ empty($_GET['active']) ? 1 : (bool) $_GET['active'] }}"
           delete="{{ empty($_GET['delete']) ? 0 : (bool) $_GET['delete'] }}"
    ></users>

@endsection
