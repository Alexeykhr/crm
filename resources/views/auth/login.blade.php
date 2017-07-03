<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CRM') }} - Авторизація</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body id="login">

<div id="app">
    <login nick="{{ old('nick') }}" remember="{{ old('remember') }}"
           error="{{ count($errors) > 0 }}" csrf="{{ csrf_token() }}"
    ></login>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
