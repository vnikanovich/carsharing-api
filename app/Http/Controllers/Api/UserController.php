<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\UserService;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return response()->json(UserResource::collection($this->userService->list()));
    }

    public function show(User $user)
    {
        return response()->json(UserResource::make($user));
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->create($data);

        return response()->json(UserResource::make($user));
    }

    public function update(User $user, UserRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->update($user, $data);

        return response()->json(UserResource::make($user));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([]);
    }
}
