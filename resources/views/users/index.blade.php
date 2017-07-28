@extends('layouts.app')

@section('title', 'Користувачі')

@section('content')

    <users i-user="{{ json_encode($me) }}"
           in-users="{{ json_encode($users) }}"
           in-roles="{{ json_encode($roles) }}"
           in-jobs="{{ json_encode($jobs) }}"
           can-create="{{ $canCreate }}"
    ></users>

@endsection
