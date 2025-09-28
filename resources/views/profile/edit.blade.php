@extends($layout)

@section('title', 'Edit Profile')

@section('content')
<div class="grid grid-cols-1 gap-6">
    <div class="bg-white shadow rounded p-4">
        <h1 class="text-2xl font-bold mb-4">Edit Profile</h1>
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="bg-white shadow rounded p-4">
        @include('profile.partials.update-password-form')
    </div>

    <div class="bg-white shadow rounded p-4">
        @include('profile.partials.delete-user-form')
    </div>
</div>
@endsection
