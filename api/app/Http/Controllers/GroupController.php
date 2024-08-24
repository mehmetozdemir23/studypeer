<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class GroupController extends Controller
{
    public function __construct(protected GroupService $groupService) {}

    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Group::class);

        $groups = $this->groupService->getForUser(request()->user());

        return response()->json($groups);
    }

    public function store(StoreGroupRequest $request): JsonResponse
    {
        $group = $this->groupService->create($request->validated());

        return response()->json($group, 201);
    }

    public function show(Group $group): JsonResponse
    {
        Gate::authorize('view', $group);

        return response()->json($group);
    }

    public function update(UpdateGroupRequest $request, Group $group): JsonResponse
    {
        $this->groupService->update($group, $request->validated());

        return response()->json($group);
    }

    public function destroy(Group $group): Response
    {
        Gate::authorize('delete', $group);

        $this->groupService->delete($group);

        return response()->noContent();
    }
}
