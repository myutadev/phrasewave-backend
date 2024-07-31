<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateRequest extends FormRequest
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
        return [
            [
                "language" => 'required | string',
                "word1" => 'required | string',
                "word2" => "nullable | string",
                "word3" => "nullable | string",
                "word4" => "nullable | string",
                "word5" => "nullable | string",
                "context1" => "nullable | string",
                "context2" => "nullable | string",
                "context3" => "nullable | string",
                "context4" => "nullable | string",
                "context5" => "nullable | string",
            ]
        ];
    }
}
