<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StoreCategoryRequest extends FormRequest
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
        Log::alert('juste pour voir si ça marche');
        return [
            'title' => 'required|unique:categories|max:100|min:4',
            'description' => 'required|max:250',
            'slug' => 'required|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/i|unique:categories'
        ];
    }
}
