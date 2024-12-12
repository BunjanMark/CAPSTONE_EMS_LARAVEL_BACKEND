<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TimeRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Implement your validation logic here
        // For example, check if the time is in a valid format
        return preg_match('/^(0[0-9]|1[0-2]):[0-5][0-9] (AM|PM)$/', $value);
    }

    public function message()
    {
        return 'The :attribute must be a valid time format (HH:MM AM/PM).';
    }
}

