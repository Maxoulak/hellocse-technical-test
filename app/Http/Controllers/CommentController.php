<?php

namespace App\Http\Controllers;

use App\Exceptions\CommentAlreadyExistsForAdminAndProfileException;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Profile;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function __construct(private readonly CommentService $commentService) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Profile $profile): CommentResource|JsonResponse
    {
        Gate::authorize('create', Comment::class);

        $validated = $request->validated();

        try {
            $comment = $this->commentService->createComment(Auth::user(), $profile, $validated);
        } catch (CommentAlreadyExistsForAdminAndProfileException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

        return new CommentResource($comment);
    }
}
