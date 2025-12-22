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
            'name.required' => 'Nama harus diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'role.required' => 'Posisi/Jabatan harus diisi.',
            'role.max' => 'Posisi/Jabatan maksimal 255 karakter.',
            'content.required' => 'Isi testimoni harus diisi.',
            'rating.required' => 'Rating harus diisi.',
            'rating.integer' => 'Rating harus berupa angka.',
            'rating.min' => 'Rating minimal 1.',
            'rating.max' => 'Rating maksimal 5.',
            'image.file' => 'Foto harus berupa file.',
            'image.mimes' => 'Format foto tidak diizinkan. Gunakan: jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran foto maksimal 2MB.',
            'image_url.url' => 'Format URL foto tidak valid.',
        ];
    }
}