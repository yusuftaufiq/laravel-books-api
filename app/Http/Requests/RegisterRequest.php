<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'string|required|max:255',
            'email' => 'email|required|max:255',
            'password' => 'required|max:255',
            'confirm_password' => 'required|same:password',
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @param  string|null  $key
     * @param  string|array|null  $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        return collect(parent::validated($key, $default))
            ->except('confirm_password')
            ->toArray();
    }
}
