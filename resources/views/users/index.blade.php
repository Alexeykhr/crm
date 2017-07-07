@extends('layouts.app')

@section('title')Користувачі@endsection

@section('content')

    <users in-users="{{ json_encode($users) }}"
           in-roles="{{ json_encode($roles) }}"
           in-jobs="{{ json_encode($jobs) }}"
           count="{{ empty($_GET['count']) ? 10 : $_GET['count'] }}"
           role="{{ empty($_GET['role']) ? -1 : $_GET['role'] }}"
           active="{{ !isset($_GET['active']) ? 0 : $_GET['active'] }}"
           delete="{{ !isset($_GET['delete']) ? -1 : $_GET['delete'] }}"
           job="{{ !isset($_GET['job']) ? -1 : $_GET['job'] }}"
           q="{{ empty($_GET['q']) ? '' : $_GET['q'] }}"
    ></users>

@endsection
