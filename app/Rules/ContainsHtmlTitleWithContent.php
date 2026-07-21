<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ContainsHtmlTitleWithContent implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if the value contains at least one non-empty HTML title tag (h1 to h6)
        return preg_match('/<h[1-6]>{\s*(?:(?!<\/).)*\S(?:(?!<\/).)*\s*}<\/h[1-6]>/i', $value) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must contain at least one HTML title tag (h1 to h6) with non-empty content.';
    }
}
