<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Services\ProfileService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProfileController extends Controller
{
    public function __construct(private readonly ProfileService $profileService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Profile::class);

        $profiles = $this->profileService->queryActiveProfiles()->paginate();

        return ProfileResource::collection($profiles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfileRequest $request): ProfileResource
    {
        Gate::authorize('create', Profile::class);

        $validated = $request->safe()->except(['image']);

        $profile = $this->profileService->createProfile(Auth::user(), $validated, $request->file('image'));

        return new ProfileResource($profile);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, Profile $profile): ProfileResource
    {
        Gate::authorize('update', $profile);

        $validated = $request->safe()->except(['image']);

        $profile = $this->profileService->updateProfile($profile, $validated, $request->file('image'));

        return new ProfileResource($profile);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile): Response
    {
        Gate::authorize('delete', $profile);

        $this->profileService->deleteProfile($profile);

        return \response(status: \Symfony\Component\HttpFoundation\Response::HTTP_NO_CONTENT);
    }
}
