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

    public function index($search)
    {
        if ($search === 'null') $search = null;
        return $this->userService->getAll($search);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = $this->userService->create($validated);

        return response()->json($user, 201);
    }

    public function show(int $id)
    {
        $category = $this->userService->find($id);
        return $category;
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $validated = $request->validated();

        $user = User::find($id);
        if(!$user){
            return response()->json(['message'=>'Usuário não encontrado'],200);
        }

        $user = $this->userService->update($user, $validated);

        return response()->json($user);
    }

    public function destroy(int $id)
    {
        return $this->userService->delete($id);
    }
}
