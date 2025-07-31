<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAll($search)
    {
        $perPage = 10;

        if ($search) {
            return User::where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->paginate($perPage);
        }

        return User::paginate($perPage);
    }


    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function find(int $id)
    {
        try {
            return User::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Usuário não encontrada.'], 404);
        }
    }

    public function update(User $user, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        return $user;
    }

    public function delete(User $user)
    {
        return $user->delete();
    }
}
