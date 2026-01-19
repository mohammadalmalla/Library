<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'ISBN' => 'required|string|max:20|unique:books,isbn',
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'mortgage' => 'required|boolean',
            'authorship_date' => 'required|date|before_or_equal:today',
            'category_id' => 'required|integer|exists:categories,id',
        ];
    }
}
