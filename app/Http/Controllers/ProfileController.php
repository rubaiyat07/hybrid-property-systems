<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $layout = 'layouts.app'; // default

        if ($user->hasRole('Agent')) {
            $layout = 'layouts.agent';
        } elseif ($user->hasRole('Landlord')) {
            $layout = 'layouts.landlord';
        } elseif ($user->hasRole('Tenant')) {
            $layout = 'layouts.tenant';
        }

        return view('profile.edit', [
            'user' => $user,
            'layout' => $layout,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $validated['profile_photo'] = $path;
        }

        if ($user->hasRole('Tenant')) {
            // For tenants, update phone and handle documents
            if (isset($validated['phone'])) {
                $user->phone = $validated['phone'];
            }

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    $path = $document->store('tenant_documents', 'public');
                    \App\Models\TenantScreening::create([
                        'tenant_id' => $user->tenant->id,
                        'document_type' => 'identification', // or determine type
                        'file_path' => $path,
                        'status' => 'pending',
                    ]);
                }
            }

            // Remove non-tenant fields from validated
            unset($validated['phone'], $validated['documents']);
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
