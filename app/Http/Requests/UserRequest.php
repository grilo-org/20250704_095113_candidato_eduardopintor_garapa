<?php

namespace GarAppa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'birthday' => '',
            'city' => 'required|min:3',
            'cpf' => '',
            'creationDate' => '',
            'email' => 'required|email|min:3',
            'firstName' => 'required|min:3',
            'fullName' => 'required|min:3',
            'gender' => 'required|min:3',
            'lastLoginDate' => '',
            'lastName' => '',
            'lastUpdate' => '',
            'phoneNumber' => '',
            'photoUrl' => '',
            'state' => '',
            'status' => '',
            'termsOfUseAcceptDate' => 'required|min:3',
            'username' => '',
        ];
    }
}
