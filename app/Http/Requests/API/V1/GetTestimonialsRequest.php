<?php

namespace App\Http\Requests\API\V1;

class GetTestimonialsRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'per_page' => 'nullable|integer|min:1|max:100',
            'status' => 'nullable|boolean',
            'rating' => 'nullable|integer|min:1|max:5',
            'page' => 'nullable|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'per_page.integer' => 'Per page must be an integer.',
            'per_page.min' => 'Per page must be at least 1.',
            'per_page.max' => 'Per page may not be greater than 100.',
            'status.boolean' => 'Status must be true or false.',
            'rating.integer' => 'Rating must be an integer.',
            'rating.min' => 'Rating must be at least 1.',
            'rating.max' => 'Rating may not be greater than 5.',
            'page.integer' => 'Page must be an integer.',
            'page.min' => 'Page must be at least 1.',
        ];
    }
}