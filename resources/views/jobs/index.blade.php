@extends('layouts.app')

@section('title', 'Посади')

@if ($canCreate)
    @section('h-btn-title', 'Додати посаду')
    @section('h-btn-icon', 'add')
    @section('h-btn-href', '/jobs/create')
    @section('h-btn-class', 'md-raised')
@endif

@section('content')

    <jobs i-user="{{ json_encode($me) }}"
          in-jobs="{{ json_encode($jobs) }}"
    ></jobs>

@endsection
