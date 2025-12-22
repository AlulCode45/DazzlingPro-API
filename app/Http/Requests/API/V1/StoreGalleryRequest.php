<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreGalleryRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
            'category' => 'nullable|string|max:100',
            'featured' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0',
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
            'title.required' => 'Judul harus diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
            'image.required' => 'Gambar harus diupload.',
            'image.file' => 'Gambar harus berupa file.',
            'image.mimes' => 'Format gambar tidak diizinkan. Gunakan: jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
            'category.max' => 'Kategori maksimal 100 karakter.',
            'featured.boolean' => 'Nilai unggulan harus berupa ya atau tidak.',
            'status.boolean' => 'Nilai status harus berupa aktif atau tidak aktif.',
            'sort_order.integer' => 'Urutan harus berupa angka.',
            'sort_order.min' => 'Urutan minimal 0.',
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
