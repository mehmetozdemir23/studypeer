<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', User::class);

        $users = $this->userService->getForUser(request()->user());

        return response()->json($users);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());

        return response()->json($user, 201);
    }

    public function show(User $user): JsonResponse
    {
        Gate::authorize('view', $user);

        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->userService->update($user, $request->validated());

        return response()->json($user);
    }

    public function destroy(User $user): Response
    {
        Gate::authorize('delete', $user);

        $this->userService->delete($user);

        return response()->noContent();
    }
}
