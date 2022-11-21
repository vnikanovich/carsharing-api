<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function list(): Collection
    {
        return User::all();
    }

    public function getById(int $id): ?User
    {
        return User::find($id);
    }

    public function hasCar(User $user): bool
    {
        return $user->cars->count();
    }

    public function usersWithCar(): Collection
    {
        return User::whereHas('cars')->get();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        $user->refresh();
        return  $user;
    }
}
