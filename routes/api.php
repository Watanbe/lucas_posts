<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);
Route::post("update-password/{id}", [AuthController::class, "resetFromBot"]);
Route::get("/test", [TestController::class, "index"]);

Route::middleware("auth:sanctum")->group(function() {
    Route::get("/posts/{id}", [PostController::class, "show"]);
    Route::get("/posts", [PostController::class, "index"]);
    Route::post("/posts", [PostController::class, "store"]);
    Route::put("/posts/{id}", [PostController::class, "update"]);
    Route::delete("/posts/{id}", [PostController::class, "destroy"]);
});
