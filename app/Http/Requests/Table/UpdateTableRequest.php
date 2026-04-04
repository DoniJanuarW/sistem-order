<?php

namespace App\Http\Requests\Table;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTableRequest extends FormRequest
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
     */
    public function rules(): array
    {     
       $tableId = $this->route('id') ?? $this->route('table');


        return [
            'table_number' => ['required','numeric',Rule::unique('tables')->ignore($tableId)],
            'status' => 'required|in:inactive,active',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'table_number.required' => 'Nomor Table wajib diisi.',
            'table_number.number' => 'Nomor Table harus berupa angka.',
            'table_number.unique' => 'Nomor Table sudah terdaftar.',
            
            'role.required' => 'Status wajib diisi.',
            'status.in' => 'Status harus salah satu dari: active, inactive.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}