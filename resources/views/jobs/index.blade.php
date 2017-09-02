@extends('layouts.app')

@section('title', 'Посади')

@if ($canCreate)
    @section('h-btn-title', 'Додати посаду')
    @section('h-btn-icon', 'add')
    @section('h-btn-href', '/jobs/create')
    @section('h-btn-class', 'md-raised')
@endif

@section('content')

    <jobs in-jobs="{{ json_encode($jobs) }}"
          can-delete="{{ json_encode($canDelete) }}"
          can-edit="{{ json_encode($canEdit) }}"
          can-transfer="{{ json_encode($canTransfer) }}"
    ></jobs>

@endsection
