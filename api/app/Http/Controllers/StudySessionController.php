<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudySessionRequest;
use App\Http\Requests\UpdateStudySessionRequest;
use App\Models\StudySession;
use App\Services\StudySessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class StudySessionController extends Controller
{
    public function __construct(protected StudySessionService $studySessionService) {}

    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', StudySession::class);

        $studySessions = $this->studySessionService->getForUser(request()->user());

        return response()->json($studySessions);
    }

    public function store(StoreStudySessionRequest $request): JsonResponse
    {
        $studySession = $this->studySessionService->create($request->validated());

        return response()->json($studySession, 201);
    }

    public function show(StudySession $studySession): JsonResponse
    {
        Gate::authorize('view', $studySession);

        return response()->json($studySession);
    }

    public function update(UpdateStudySessionRequest $request, StudySession $studySession): JsonResponse
    {
        $this->studySessionService->update($studySession, $request->validated());

        return response()->json($studySession);
    }

    public function destroy(StudySession $studySession): Response
    {
        Gate::authorize('delete', $studySession);

        $this->studySessionService->delete($studySession);

        return response()->noContent();
    }
}
