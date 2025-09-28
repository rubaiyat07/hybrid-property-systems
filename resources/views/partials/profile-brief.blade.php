<div class="bg-white shadow rounded p-4">
    <h3 class="font-semibold mb-4">Profile Completion</h3>
    <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
        <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $profileCompletion }}%"></div>
    </div>
    <p class="text-sm mb-4">{{ $profileCompletion }}% completed</p>

    <ul class="text-sm space-y-2">
        @if(!$profile->profile_photo)
            <li>Upload your profile photo</li>
        @endif
        @if(!$profile->phone_verified)
            <li>Verify your phone number</li>
        @endif
        @if(!$profile->bio)
            <li>Add a short bio</li>
        @endif
        @if(!$profile->documents_verified)
            <li>Upload verification documents</li>
        @endif
        @if(!$profile->screening_verified)
            <li>Complete tenant screening</li>
        @endif
    </ul>

    <a href="{{ route('profile.edit') }}" class="mt-4 inline-block text-indigo-600 font-medium hover:underline">
        Complete your profile
    </a>
</div>
