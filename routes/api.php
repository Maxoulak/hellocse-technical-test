<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::apiResource('profile', ProfileController::class)->except(['show']);
