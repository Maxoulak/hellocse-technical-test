<?php

namespace App\Services;

use App\Exceptions\UnableToStoreMediaException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaService
{
    /**
     * Store a user uploaded media
     *
     * @return string The media's path
     */
    public function storeMedia(UploadedFile $media): string
    {
        $path = $media->store('uploads');

        if ($path === false) {
            throw new UnableToStoreMediaException();
        }

        return $path;
    }

    public function publicUrl(string $path): string
    {
        return Storage::url($path);
    }

    /**
     * Delete a media
     */
    public function deleteMedia(string $path): bool
    {
        return Storage::delete($path);
    }
}
