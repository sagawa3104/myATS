<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $unique = Rule::unique('users', 'email');
        $unique = isset($this->user)? $unique->ignore($this->user->id):$unique;

        $password = isset($this->user)? 'nullable|string|min:8': 'required|string|min:8';
        return [
            'name' => 'required|string|max:255',
            'email' => ['required','string','email', 'max:255', $unique],
            'password' => $password,
        ];
    }
}
