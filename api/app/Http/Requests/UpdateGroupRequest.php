<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('group'));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:8', 'max:100'],
            'description' => ['required', 'string', 'min:8', 'max:200'],
        ];
    }
}
