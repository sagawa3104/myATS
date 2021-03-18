<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
        $unique = Rule::unique('projects', 'code');
        $unique = isset($this->project)? $unique->ignore($this->project->id):$unique;
        return [
            'code' => ['required','string','max:255',$unique],
            'name' => 'required|string|max:255',
        ];
    }
}
