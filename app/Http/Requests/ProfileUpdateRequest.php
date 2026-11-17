<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'telefono' => ['nullable', 'string', 'max:30'],
            'celular' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];

        $miembro = $this->user()->miembro;

        if ($miembro) {
            $rules = array_merge($rules, [
                'tipo_sangre' => ['nullable', 'string', 'max:10'],
                'numero_referencia' => [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('miembros', 'numero_referencia')->ignore($miembro->id),
                ],
                'contacto_emergencia' => ['nullable', 'string', 'max:255'],
                'telefono_emergencia' => ['nullable', 'string', 'max:30'],
                'estado_civil' => ['nullable', 'string', 'max:50'],
                'conyuge' => ['nullable', 'string', 'max:255'],
                'hijos' => ['nullable', 'integer', 'min:0'],
                'profesion' => ['nullable', 'string', 'max:255'],
            ]);
        }

        return $rules;
    }
}
