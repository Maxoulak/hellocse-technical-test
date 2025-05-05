<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::apiResource('profile', ProfileController::class)->except(['show']);

Route::post('profile/{profile}/comment', [CommentController::class, 'store']);
