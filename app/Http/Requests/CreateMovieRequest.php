<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {
        return [
                'title' => 'required|string|max:120',
                'description' => 'required|string|max:320',
                'image' => 'nullable|image|max:350',
                'category_id' => 'required|exists:categories,id'
        ];
    }
}
