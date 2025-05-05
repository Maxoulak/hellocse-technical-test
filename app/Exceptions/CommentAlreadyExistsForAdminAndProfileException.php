<?php

namespace App\Exceptions;

use App\Models\Admin;
use App\Models\Profile;
use Symfony\Component\HttpFoundation\Response;

class CommentAlreadyExistsForAdminAndProfileException extends \Exception
{
    public function __construct(Admin $admin, Profile $profile, int $code = Response::HTTP_CONFLICT, ?\Throwable $previous = null)
    {
        parent::__construct(
            "A comment has already been submitted for the profile {$profile->id} by the admin {$admin->id}",
            $code,
            $previous
        );
    }
}
