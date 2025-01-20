<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostCapitalAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'capital' => 'required|string|max:255',
        ];
    }
}
