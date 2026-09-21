<?php

namespace App\Http\Requests\UserManagement;

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateKaryawanRequest extends FormRequest
{
    use ProfileValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $karyawan = $this->route('karyawan');

        return $karyawan instanceof User
            && ($this->user()?->can('update', $karyawan) ?? false);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $karyawan = $this->route('karyawan');

        return [
            ...$this->profileRules(
                $karyawan instanceof User ? $karyawan->id : null,
            ),
            'operational_function_id' => [
                'required',
                'integer',
                Rule::exists('operational_functions', 'id')
                    ->where(function ($query) use ($karyawan): void {
                        $query->where(function ($query) use ($karyawan): void {
                            $query->where('is_active', true);

                            if ($karyawan instanceof User && $karyawan->operational_function_id !== null) {
                                $query->orWhere('id', $karyawan->operational_function_id);
                            }
                        });
                    }),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan.',
            'operational_function_id.required' => 'Fungsi operasional wajib dipilih.',
            'operational_function_id.integer' => 'Fungsi operasional yang dipilih tidak valid.',
            'operational_function_id.exists' => 'Fungsi operasional yang dipilih tidak tersedia.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $email = $this->input('email');

        if (is_string($email)) {
            $this->merge([
                'email' => Str::lower(trim($email)),
            ]);
        }
    }
}
