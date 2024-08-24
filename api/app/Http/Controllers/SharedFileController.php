<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSharedFileRequest;
use App\Http\Requests\UpdateSharedFileRequest;
use App\Models\Resource;
use App\Models\SharedFile;
use App\Services\SharedFileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SharedFileController extends Controller
{
    public function __construct(protected SharedFileService $sharedFileService)
    {
    }
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', SharedFile::class);

        $sharedFiles = $this->sharedFileService->getForUser(request()->user());

        return response()->json($sharedFiles);
    }

    public function store(StoreSharedFileRequest $request)
    {
        $sharedFile = $this->sharedFileService->create($request->validated());

        return response()->json($sharedFile, 201);
    }

    public function show(SharedFile $sharedFile)
    {
        Gate::authorize('view', $sharedFile);

        return response()->json($sharedFile);
    }

    public function update(UpdateSharedFileRequest $request, SharedFile $sharedFile)
    {
        $this->sharedFileService->update($sharedFile, $request->validated());

        return response()->json($sharedFile);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SharedFile $sharedFile)
    {
        Gate::authorize('delete', $sharedFile);

        $this->sharedFileService->delete($sharedFile);

        return response()->noContent();
    }
}
