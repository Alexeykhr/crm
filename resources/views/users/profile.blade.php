@extends('layouts.app')

@section('title')Профіль користувача {{ $user->name }}@endsection

@section('content')

    <?php dd($user); ?>

@endsection
