<?php

namespace App\Services;

use App\Exceptions\CommentAlreadyExistsForAdminAndProfileException;
use App\Models\Admin;
use App\Models\Comment;
use App\Models\Profile;

class CommentService
{
    /**
     * @param array<string, mixed> $data
     */
    public function createComment(Admin $admin, Profile $profile, array $data): Comment
    {
        if ($this->hasAlreadyPostComment($admin, $profile)) {
            throw new CommentAlreadyExistsForAdminAndProfileException($admin, $profile);
        }

        $comment = new Comment($data);
        $comment->admin()->associate($admin);
        $comment->profile()->associate($profile);
        $comment->save();

        return $comment;
    }

    public function hasAlreadyPostComment(Admin $admin, Profile $profile): bool
    {
        return $admin->comments()->where('comments.profile_id', $profile->id)->exists();
    }
}
