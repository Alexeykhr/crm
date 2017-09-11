@extends('layouts.app')

@section('title', 'Журнал')

@section('content')

    <logs :me="{{ json_encode($me) }}"
          :in-logs="{{ json_encode($logs) }}"
    ></logs>

@endsection
