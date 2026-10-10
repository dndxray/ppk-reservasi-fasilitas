<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Samakan format nomor telepon sebelum divalidasi
     * (0812..., 62812..., +62812... -> 0812...).
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('no_telepon')) {
            $this->merge([
                'no_telepon' => User::normalisasiTelepon($this->input('no_telepon')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'nim_nip' => ['nullable', 'string', 'max:50'],
            'no_telepon' => [
                'nullable',
                'regex:/^08[1-9][0-9]{7,10}$/',
                Rule::unique(User::class, 'no_telepon')->ignore($this->user()->id),
            ],
        ];
    }

    /**
     * Pesan error dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'no_telepon.regex'  => 'Nomor telepon tidak valid. Gunakan format 08xxxxxxxxxx.',
            'no_telepon.unique' => 'Nomor telepon ini sudah terdaftar.',
        ];
    }
}