@extends('layouts.app')

@section('title', 'Календар')

@section('content')

    <calendar i-user="{{ json_encode($me) }}"
              in-users="{{ json_encode($users) }}"
              in-month="{{ $month }}"
              in-year="{{ $year }}"
    ></calendar>

@endsection

