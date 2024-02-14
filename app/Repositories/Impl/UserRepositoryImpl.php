<?php

namespace App\Repositories\Impl;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepositoryImpl implements UserRepository
{
    public function getUsers(array $data): LengthAwarePaginator
    {
        $search = $data['search'] ?? '';
        $page = $data['page'] ?? 1;
        $perPage = $data['per_page'] ?? 10;

        $query = User::query();
        if($search){
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function save(array $data): User
    {
        return User::query()->create($data);
    }

    public function update(User $user, $data): bool
    {
        return $user->update($data);
    }

    public function findByColumn(string $column, mixed $value): User
    {
        return User::query()->where($column, $value)->firstOrFail();
    }
}
