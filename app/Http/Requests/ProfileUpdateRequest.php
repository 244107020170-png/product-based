<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Validation rules for profile update.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'username' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9_.]+$/',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            'gender'           => ['nullable', 'string', 'in:laki-laki,perempuan'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'bio'              => ['nullable', 'string', 'max:500'],
            'sport_preference' => ['nullable', 'string', 'max:255'],

            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /** Pesan validasi dalam Bahasa Indonesia */
    public function messages(): array
    {
        return [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'username.required'  => 'Username wajib diisi.',
            'username.regex'     => 'Username hanya boleh huruf kecil, angka, titik, dan underscore.',
            'username.unique'    => 'Username sudah dipakai, coba yang lain.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar.',
            'avatar.image'       => 'File harus berupa gambar.',
            'avatar.max'         => 'Ukuran foto maksimal 2MB.',
        ];
    }

    /** Normalize username to lowercase before validation */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => Str::lower(trim((string) $this->input('username'))),
        ]);
    }
}
