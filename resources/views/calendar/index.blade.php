@extends('layouts.app')

@section('title', 'Календар')

@section('content')

    <calendar :me="{{ json_encode($me) }}"
              :in-users="{{ json_encode($users) }}"
              :in-month="{{ json_encode($month) }}"
              :in-year="{{ json_encode($year) }}"
    ></calendar>

@endsection

