@extends('layouts.app')

@section('title', 'Створення посади')

@if ($canView)
    @section('h-btn-title', 'Всі посади')
    @section('h-btn-icon', 'arrow_back')
    @section('h-btn-href', '/jobs')
    @section('h-btn-class', 'md-raised')
@endif

@section('content')

    <jobs-page action="{{ $action }}"
    ></jobs-page>

@endsection
