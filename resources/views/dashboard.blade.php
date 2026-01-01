@extends('layouts.auth')

@section('title', 'My Ablelink space')

@section('content')
    <h1>Welcome back, {{ auth()->user()->name }}</h1>
    <p>Your role: <strong>{{ ucfirst(auth()->user()->role) }}</strong></p>

    <p>Last login: {{ optional(auth()->user()->last_login_at)->diffForHumans() ?? 'first time here' }}</p>

    <p>Use this space to stay connected with your care circle. Admins can reach out if they need more information.</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Sign out</button>
    </form>
@endsection
