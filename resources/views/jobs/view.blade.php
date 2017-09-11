@extends('layouts.app')

@section('title', 'Перегляд посади')

@section('h-btn-title', 'Всі посади')
@section('h-btn-icon', 'arrow_back')
@section('h-btn-href', '/jobs')
@section('h-btn-class', 'md-raised')

@section('content')

    <jobs-page :job="{{ json_encode($job) }}"
               action="{{ $action }}"
               :can-edit="{{ json_encode($canEdit) }}"
               :can-create="{{ json_encode($canCreate) }}"
    ></jobs-page>

@endsection
