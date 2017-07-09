@extends('layouts.app')

@section('title')Журнал@endsection

@section('content')

    <logs data="{{ json_encode($logs) }}"></logs>

@endsection
