<?php

namespace App\Http\Requests;

use App\Models\Group;
use App\Models\SharedFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreSharedFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $group = Group::find($this->input('group_id'));

        return Gate::allows('create', [SharedFile::class, $group]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'group_id' => ['required', 'exists:groups,id'],
            'file' => ['required', 'file'],
            'name' => ['required', 'string', 'unique:shared_files,name', 'min:8', 'max:100'],
            'description' => ['nullable', 'string', 'min:8', 'max:200'],
        ];
    }
}
