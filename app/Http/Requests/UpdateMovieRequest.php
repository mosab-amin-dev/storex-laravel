<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
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
    public function rules()
    {
        return [
                'title'=>'nullable|string|max:120',
                'description'=>'nullable|string|max:320',
                'image'=>'nullable|image|max:350',
                'rate'=>'nullable|numeric|between:0,5',
                'category_id'=>'nullable|exists:categories,id'
        ];
    }
}
