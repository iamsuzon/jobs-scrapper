@extends('layouts.app')

@section('styles')
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }

        .login-form {
            width: 25dvw;
            max-width: 50dvw;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="login-form p-4">
        <x-messages/>

        <h2 class="text-center mb-4">Login</h2>
        <form method="POST" action="{{route('admin.login')}}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Username / Email</label>
                <input type="text" class="form-control w-100" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control w-100" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label>
                    <input name="remember_me"  type="checkbox">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Login</button>
        </form>
    </div>
@endsection
