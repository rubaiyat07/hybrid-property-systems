@extends('layouts.guest')

@section('title', 'Register - HybridEstate')

@section('content')
    <section class="login-section">
        <div class="login-card">
            <h1 class="login-title">Register</h1>
            <p class="login-subtitle">Create your account to get started</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <x-input-label for="name" :value="__('Name')" />
                    <input id="name" type="text" name="name" value="{{ old('name') }}" 
                           class="form-input" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="form-group">
                    <x-input-label for="email" :value="__('Email')" />
                    <input id="email" type="email" name="email" value="{{ old('email') }}" 
                           class="form-input" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <x-input-label for="password" :value="__('Password')" />
                    <input id="password" type="password" name="password" 
                           class="form-input" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <input id="password_confirmation" type="password" name="password_confirmation" 
                           class="form-input" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <a href="{{ route('login') }}" class="forgot-link">
                        {{ __('Already registered?') }}
                    </a>

                    <button type="submit" class="cta-btn">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
