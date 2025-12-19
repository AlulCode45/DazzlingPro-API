<?php

namespace App\Http\Requests\API\V1;

class UpdateTestimonialRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'role' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url' => 'nullable|url',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'role.required' => 'The role field is required.',
            'role.max' => 'The role may not be greater than 255 characters.',
            'content.required' => 'The content field is required.',
            'rating.required' => 'The rating field is required.',
            'rating.integer' => 'The rating must be an integer.',
            'rating.min' => 'The rating must be at least 1.',
            'rating.max' => 'The rating may not be greater than 5.',
            'image.file' => 'The image must be a file.',
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, png, webp.',
            'image.max' => 'The image may not be greater than 2MB.',
            'image_url.url' => 'The image URL must be a valid URL.',
        ];
    }
}