<?php

namespace App\Http\Requests;

use App\Models\Group;
use App\Models\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $group = Group::find($this->input('group_id'));

        return Gate::allows('create', [Message::class, $group]);
    }

    public function rules(): array
    {
        return [
            'sender_id' => ['required', 'exists:users,id'],
            'receiver_id' => ['required', 'exists:users,id'],
            'group_id' => ['required', 'exists:groups,id'],
            'content' => ['required', 'string'],
        ];
    }
}
