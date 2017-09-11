@extends('layouts.app')

@section('title', 'Редагування посади')

@if ($canView)
    @section('h-btn-title', 'Всі посади')
    @section('h-btn-icon', 'arrow_back')
    @section('h-btn-href', '/jobs')
    @section('h-btn-class', 'md-raised')
@endif

@section('content')

    <jobs-page in-job="{{ json_encode($job) }}"
               action="{{ $action }}"
               can-view="{{ $canView }}"
               can-delete="{{ $canDelete }}"
               can-transfer="{{ $canTransfer }}"
               can-create="{{ $canCreate }}"
    ></jobs-page>

@endsection
