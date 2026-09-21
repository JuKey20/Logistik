<?php

namespace App\Http\Requests\OperationalFunction;

use App\Models\OperationalFunction;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateOperationalFunctionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $operationalFunction = $this->route('operational_function');

        return $operationalFunction instanceof OperationalFunction
            && ($this->user()?->can('update', $operationalFunction) ?? false);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->has('name')) {
                    return;
                }

                $name = $this->input('name');
                $operationalFunction = $this->route('operational_function');

                if (! is_string($name)) {
                    return;
                }

                $query = OperationalFunction::query()
                    ->where('normalized_name', OperationalFunction::normalizeName($name));

                if ($operationalFunction instanceof OperationalFunction) {
                    $query->whereKeyNot($operationalFunction->getKey());
                }

                if ($query->exists()) {
                    $validator->errors()->add('name', 'Nama fungsi operasional sudah digunakan.');
                }
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama fungsi operasional wajib diisi.',
            'name.string' => 'Nama fungsi operasional harus berupa teks.',
            'name.max' => 'Nama fungsi operasional maksimal 100 karakter.',
        ];
    }
}
