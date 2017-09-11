@extends('layouts.app')

@section('title', 'Редагування посади')

@if ($canView)
    @section('h-btn-title', 'Всі посади')
    @section('h-btn-icon', 'arrow_back')
    @section('h-btn-href', '/jobs')
    @section('h-btn-class', 'md-raised')
@endif

@section('content')

    <jobs-page :job="{{ json_encode($job) }}"
               action="{{ $action }}"
               :can-view="{{ json_encode($canView) }}"
               :can-delete="{{ json_encode($canDelete) }}"
               :can-transfer="{{ json_encode($canTransfer) }}"
               :can-create="{{ json_encode($canCreate) }}"
    ></jobs-page>

@endsection
