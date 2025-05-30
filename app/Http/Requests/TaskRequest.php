<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:todo,in_progress,completed',
            'priority' => 'nullable|string|in:high,medium,low',
            'category_id' => 'nullable|exists:categories,id',
            'due_date' => 'nullable|date',
        ];
    }
}
