<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        Log::info($this);

        $id = $this->route('id');

        return [
            'name' => 'unique:quizzes,name,' . $id . '|max:100|min:4',
            'description' => 'max:250',
            'slug' => 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/i|unique:quizzes,slug,' . $id
        ];
    }
}
