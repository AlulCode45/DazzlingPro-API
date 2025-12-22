<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePartnerRequest extends FormRequest
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
            'logo' => 'sometimes|file|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
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
            'name.required' => 'Nama partner harus diisi.',
            'name.string' => 'Nama partner harus berupa teks.',
            'name.max' => 'Nama partner maksimal 255 karakter.',
            'logo.file' => 'Logo harus berupa file.',
            'logo.mimes' => 'Format logo tidak diizinkan. Gunakan: jpg, jpeg, png, webp, atau svg.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'website.url' => 'Format website tidak valid.',
            'website.max' => 'Website maksimal 255 karakter.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
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
