<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePortfolioRequest extends FormRequest
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
        $portfolioId = $this->route('portfolio')->id ?? $this->route('portfolio');

        return [
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:portfolios,slug,' . $portfolioId,
            'description' => 'sometimes|required|string',
            'short_description' => 'nullable|string|max:500',
            'client_name' => 'nullable|string|max:255',
            'event_date' => 'nullable|date',
            'event_location' => 'nullable|string|max:255',
            'portfolio_category_id' => 'sometimes|required|exists:portfolio_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'sometimes|file|mimes:jpg,jpeg,png,webp|max:5120',
            'image_urls' => 'nullable|array',
            'image_urls.*' => 'nullable|string',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'nullable|string',
            'featured_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'featured_image_url' => 'nullable|string',
            'delete_featured_image' => 'sometimes|boolean',
            'featured' => 'sometimes|boolean',
            'completed' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0'
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
            'slug.required' => 'Slug harus diisi.',
            'slug.unique' => 'Slug sudah digunakan. Gunakan slug yang berbeda.',
            'description.required' => 'Deskripsi harus diisi.',
            'short_description.max' => 'Deskripsi singkat maksimal 500 karakter.',
            'client_name.max' => 'Nama klien maksimal 255 karakter.',
            'event_date.date' => 'Format tanggal tidak valid.',
            'event_location.max' => 'Lokasi acara maksimal 255 karakter.',
            'portfolio_category_id.required' => 'Kategori harus dipilih.',
            'portfolio_category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'images.array' => 'Format gambar tidak valid.',
            'images.*.file' => 'Setiap gambar harus berupa file.',
            'images.*.mimes' => 'Format gambar tidak diizinkan. Gunakan: jpg, jpeg, png, atau webp.',
            'images.*.max' => 'Ukuran gambar maksimal 5MB.',
            'image_urls.array' => 'Format URL gambar tidak valid.',
            'existing_images.array' => 'Format gambar yang ada tidak valid.',
            'featured_image.file' => 'Gambar utama harus berupa file.',
            'featured_image.mimes' => 'Format gambar utama tidak diizinkan. Gunakan: jpg, jpeg, png, atau webp.',
            'featured_image.max' => 'Ukuran gambar utama maksimal 5MB.',
            'delete_featured_image.boolean' => 'Nilai hapus gambar utama tidak valid.',
            'featured.boolean' => 'Nilai unggulan harus berupa ya atau tidak.',
            'completed.boolean' => 'Nilai selesai harus berupa ya atau tidak.',
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
