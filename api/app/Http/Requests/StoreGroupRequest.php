<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', Group::class);
    }

    public function rules(): array
    {
        return [
            'creator_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'min:8', 'max:100'],
            'description' => ['required', 'string', 'min:8', 'max:200'],
        ];
    }
}
