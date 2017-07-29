@extends('layouts.app')

@section('title', 'Журнал')

@section('content')

    <logs i-user="{{ $me }}"
          in-logs="{{ json_encode($logs) }}"
    ></logs>

@endsection
