<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;

trait Validatable
{
    public function validate()
    {
        $validator = Validator::make($this->attributes, $this->rules());
        $validator->validate();
        return true;
    }
}
