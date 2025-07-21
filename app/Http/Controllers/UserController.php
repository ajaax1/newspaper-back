<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Contracts\Cache\Store;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        return $this->userService->getAll($perPage);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = $this->userService->create($validated);

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user = $this->userService->update($user, $validated);

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user);
        return response()->json(null, 204);
    }
}
