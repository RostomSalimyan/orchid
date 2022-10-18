<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'client.phone' =>  ['sometimes', 'phone:RU', 'required'],
            'client.name' => ['required'],
            'client.last_name' => ['required'],
            'client.email' => ['required', 'email', 'unique:clients,email'],
            'client.birthday' => ['required', 'date_format:Y-m-d'],
            'client.service_id' => ['exists:services,id', 'required'],
            'client.assessment' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'client.phone.phone' => 'Номер телефона в неверном формате'
        ];
    }
}
