<?php

namespace App\Http\Requests\Expense;

use App\Rules\LteValidationDate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'date' => ['date_format:Y-m-d H:i:s', 'required', new LteValidationDate()],
            'value' => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string', 'max:191'],
        ];
    }
}
