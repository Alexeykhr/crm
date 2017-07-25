@extends('layouts.app')

@section('title', 'Журнал')

@section('content')

    <logs data="{{ json_encode($logs) }}"></logs>

@endsection
