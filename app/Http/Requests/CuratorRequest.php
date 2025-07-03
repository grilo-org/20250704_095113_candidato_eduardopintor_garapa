<?php

namespace GarAppa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CuratorRequest extends FormRequest
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
            'biography'       => 'required|min:3',
            'city'      => 'required|min:3',
            'name'      => 'required|min:3',
            'occupation'    => 'required|min:3',
            'state'     => 'required',
            // 'email'     => 'unique:users,email'
        ];
    }
}
