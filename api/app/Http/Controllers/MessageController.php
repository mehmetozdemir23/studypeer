<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    public function __construct(protected MessageService $messageService) {}

    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Message::class);

        $messages = $this->messageService->getForUser(request()->user());

        return response()->json($messages);
    }

    public function store(StoreMessageRequest $request): JsonResponse
    {
        $message = $this->messageService->create($request->validated());

        return response()->json($message, 201);
    }

    public function show(Message $message): JsonResponse
    {
        Gate::authorize('view', $message);

        return response()->json($message);
    }

    public function update(UpdateMessageRequest $request, Message $message): JsonResponse
    {
        $this->messageService->update($message, $request->validated());

        return response()->json($message);
    }

    public function destroy(Message $message): Response
    {
        Gate::authorize('delete', $message);

        $this->messageService->delete($message);

        return response()->noContent();
    }
}
