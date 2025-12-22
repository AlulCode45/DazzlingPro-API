<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTeamRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|string|max:255',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required|string|max:50',
            'social_links.*.url' => 'required|url|max:255',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'position.required' => 'Posisi harus diisi.',
            'position.string' => 'Posisi harus berupa teks.',
            'position.max' => 'Posisi maksimal 255 karakter.',
            'department.max' => 'Departemen maksimal 255 karakter.',
            'bio.string' => 'Bio harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'photo.image' => 'Foto harus berupa gambar.',
            'photo.mimes' => 'Format foto tidak diizinkan. Gunakan: jpeg, png, jpg, gif, atau webp.',
            'photo.max' => 'Ukuran foto maksimal 5MB.',
            'social_links.array' => 'Format social media tidak valid.',
            'social_links.*.platform.required' => 'Platform social media harus diisi.',
            'social_links.*.platform.max' => 'Platform maksimal 50 karakter.',
            'social_links.*.url.required' => 'URL social media harus diisi.',
            'social_links.*.url.url' => 'Format URL social media tidak valid.',
            'social_links.*.url.max' => 'URL maksimal 255 karakter.',
            'skills.array' => 'Format skill tidak valid.',
            'skills.*.max' => 'Setiap skill maksimal 100 karakter.',
            'is_active.boolean' => 'Status aktif harus berupa ya atau tidak.',
            'is_featured.boolean' => 'Status unggulan harus berupa ya atau tidak.',
            'order.integer' => 'Urutan harus berupa angka.',
            'order.min' => 'Urutan minimal 0.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Data yang dimasukkan tidak valid.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
