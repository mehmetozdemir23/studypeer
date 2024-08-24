<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateStudySessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('study_session'));
    }
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:8', 'max:100'],
            'description' => ['required', 'string', 'min:8', 'max:200'],
            'scheduled_at' => ['required', 'date_format:Y-m-d H:i:s', 'after:now']
        ];
    }
}
