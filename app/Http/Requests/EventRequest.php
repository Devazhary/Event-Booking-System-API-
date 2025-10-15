<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->role == 'admin') {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isCreate = $this->method() == 'POST';
        return [
            'title' => [$isCreate ? 'required' : 'nullable', 'string', 'max:255'],
            'description' => [$isCreate ? 'required' : 'nullable', 'string', 'max:255'],
            'date' => [$isCreate ? 'required' : 'nullable', 'date'],
            'location' => [$isCreate ? 'required' : 'nullable', 'string', 'max:255'],
            'available_seats' => [$isCreate ? 'required' : 'nullable', 'integer', 'min:0'],
            'category_id' => [$isCreate ? 'required' : 'nullable', 'exists:categories,id'],
            'is_active' => [$isCreate ? 'required' : 'nullable', 'boolean']
        ];
    }
}
