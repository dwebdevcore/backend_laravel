<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Uuid implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !(!is_string($value) || (preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-[1-4][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $value) !== 1));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid uuid string.';
    }
}
