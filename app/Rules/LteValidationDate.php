<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class LteValidationDate implements InvokableRule
{
    public function __invoke($attribute, $value, $fail)
    {
        if ($value > now()->setTimezone('America/Sao_Paulo')->format('Y-m-d H:i:s')) {
            return $fail('invalid-date');
        }

        return true;
    }
}
