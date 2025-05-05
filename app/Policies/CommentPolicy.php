<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        return true;
    }
}
