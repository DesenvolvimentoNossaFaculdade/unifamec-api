<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamLocationRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer essa requisição.
     * A segurança real (403) é feita pelo Controller ($this->authorize),
     * então aqui deixamos true para permitir que a validação ocorra.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para criação e atualização.
     */
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'city'      => ['required', 'string', 'max:255'],
            'date'      => ['required', 'string', 'max:255'],
            'time'      => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Prepara os dados antes da validação.
     * Útil para converter camelCase (Front) para snake_case (Banco).
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('isActive')) {
            $this->merge([
                'is_active' => $this->boolean('isActive'),
            ]);
        } elseif ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->boolean('is_active'),
            ]);
        }
    }
}