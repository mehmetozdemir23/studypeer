<?php

namespace App\Http\Requests;

use App\Models\Group;
use App\Models\StudySession;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreStudySessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $group = Group::find($this->input('group_id'));

        return Gate::allows('create', [StudySession::class, $group]);
    }

    public function rules(): array
    {
        return [
            'group_id' => ['required', 'exists:groups,id'],
            'title' => ['required', 'string', 'min:8', 'max:100'],
            'description' => ['required', 'string', 'min:8', 'max:200'],
            'scheduled_at' => ['required', 'date_format:Y-m-d H:i:s', 'after:now']
        ];
    }
}
