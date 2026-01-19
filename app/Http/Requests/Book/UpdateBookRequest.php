<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
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
        $book = $this->route('book');
        return [
            'ISBN' => ['sometimes|required|string|max:20',Rule::unique('books','ISBN')->ignore($book->id)],
            'title' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'mortgage' => 'sometimes|required|boolean',
            'authorship_date' => 'sometimes|required|date|before_or_equal:today',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
        ];
    }
}
