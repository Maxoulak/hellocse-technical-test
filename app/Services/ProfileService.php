<?php

namespace App\Services;

use App\Enums\ProfileStatus;
use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    public function __construct(private readonly MediaService $mediaService)
    {
    }

    public function queryActiveProfiles(): Builder
    {
        return Profile::query()
            ->where('status', ProfileStatus::ACTIVE);
    }

    public function createProfile(Admin $admin, array $data, ?UploadedFile $image = null): Profile
    {
        try {
            DB::beginTransaction();

            $data['image'] = null;

            $profile = new Profile($data);
            $profile->admin()->associate($admin);
            $profile->save();

            if ($image !== null) {
                $profile->update([
                    'image' => $this->mediaService->storeMedia($image),
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return $profile;
    }

    public function updateProfile(Profile $profile, array $data, ?UploadedFile $image = null): Profile
    {
        try {
            DB::beginTransaction();

            $oldImage = $profile->image;

            $profile->update($data);

            if ($image !== null) {
                $profile->update([
                    'image' => $this->mediaService->storeMedia($image),
                ]);

                if ($oldImage !== null) {
                    $this->mediaService->deleteMedia($oldImage);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return $profile;
    }

    public function deleteProfile(Profile $profile): void
    {
        try {
            DB::beginTransaction();

            $image = $profile->image;

            $profile->delete();

            if ($image !== null) {
                $this->mediaService->deleteMedia($image);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
