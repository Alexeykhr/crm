@extends('layouts.app')

@section('title', 'Перегляд посади')

@section('h-btn-title', 'Всі посади')
@section('h-btn-icon', 'arrow_back')
@section('h-btn-href', '/jobs')
@section('h-btn-class', 'md-raised')

@section('content')

    <jobs-page i-user="{{ json_encode($me) }}"
               in-job="{{ json_encode($job) }}"
               action="{{ $action }}"
               can-edit="{{ $canEdit }}"
    ></jobs-page>

@endsection
