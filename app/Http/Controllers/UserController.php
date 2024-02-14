<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\GetUsersRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        protected UserRepository $userRepository
    )
    {
    }

    /**
     * Handle the request to get a list of users.
     *
     * @param GetUsersRequest $request
     *
     * @return JsonResponse
     */
    public function handleGetUsers(GetUsersRequest $request): JsonResponse
    {
        $data = $request->validated();
        $users = $this->userRepository->getUsers($data);

        return response()->json([
            'items' => $users->items(),
            'total' => $users->lastPage(),
        ]);
    }

    /**
     * Handle the request to delete a user by ID.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function handleDeleteUser(int $id): JsonResponse
    {
        $user = $this->userRepository->findByColumn('id', $id);
        $user->delete();

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle the request to update a user by ID.
     *
     * @param UpdateUserRequest $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function handleUpdateUser(UpdateUserRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();

        $user = $this->userRepository->findByColumn('id', $id);
        $this->userRepository->update($user, $data);

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle the request to create a new user.
     *
     * @param CreateUserRequest $request
     *
     * @return JsonResponse
     */
    public function handleCreateUser(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->userRepository->save($data);

        return response()->json(['status' => 'success']);
    }
}
