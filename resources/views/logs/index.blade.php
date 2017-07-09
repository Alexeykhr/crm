@extends('layouts.app')

@section('title')Журнал@endsection

@section('content')

    <logs data="{{ $logs }}"></logs>

@endsection
