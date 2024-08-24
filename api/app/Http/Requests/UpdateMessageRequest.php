<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('message'));
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string']
        ];
    }
}
