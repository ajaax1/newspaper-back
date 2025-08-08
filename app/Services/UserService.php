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
        return User::find($id);
    }

    public function update(User $user, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        return response()->json(['message' => 'Atualizado com Sucesso'], 200);
    }

    public function delete(int $id)
    {

        $authUser = auth()->user();

        if ($authUser && $authUser->id === $id) {
            return response()->json(['message' => 'Você não pode deletar a si mesmo.'], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 200);
        }
        $user->delete();

        return response()->json(['message' => 'Usuário deletado com sucesso!'], 200);
    }
}
