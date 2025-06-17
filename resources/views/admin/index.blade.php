@extends('adminlte::page')

@section('title', 'Municipalidad JLO')

@section('content_header')
    <h1>Municipalidad JLO</h1>
@stop

@section('content')
    <p>Bienvenidos al panel de administración.</p>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop