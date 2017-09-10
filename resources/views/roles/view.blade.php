@extends('layouts.app')

@section('title', 'Перегляд ролі')

@section('h-btn-title', 'Всі ролі')
@section('h-btn-icon', 'arrow_back')
@section('h-btn-href', '/roles')
@section('h-btn-class', 'md-raised')

@section('content')

    <roles-page i-user="{{ json_encode($me) }}"
                in-role="{{ json_encode($role) }}"
                action="{{ $action }}"
                can-edit="{{ $canEdit }}"
    ></roles-page>

@endsection
