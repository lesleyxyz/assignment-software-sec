<?php

namespace App\Actions\Fortify;

use Valorin\Pwned\Pwned;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        return ['required', 'string', new Pwned(300), new Password, 'confirmed'];
    }
}
