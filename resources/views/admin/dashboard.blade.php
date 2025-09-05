{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h1>Welcome, {{ auth()->user()->name }}</h1>
<p>This is your admin dashboard.</p>
@endsection
