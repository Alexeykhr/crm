@extends('layouts.app')

@section('title', 'Авторизація')

@section('body-classes', 'login')

@section('content')

    <login nick="{{ old('nick') }}" remember="{{ old('remember') }}"
           error="{{ count($errors) > 0 }}" csrf="{{ csrf_token() }}"
    ></login>

@endsection

