<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TurkishIdentityNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $tc = preg_replace('/\s+/', '', (string) $value);

        if (! preg_match('/^[1-9][0-9]{10}$/', $tc)) {
            $fail('Geçerli bir T.C. kimlik numarası girin.');

            return;
        }

        $digits = array_map('intval', str_split($tc));
        $oddSum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8];
        $evenSum = $digits[1] + $digits[3] + $digits[5] + $digits[7];

        if ((($oddSum * 7) - $evenSum) % 10 !== $digits[9]) {
            $fail('Geçerli bir T.C. kimlik numarası girin.');

            return;
        }

        if (array_sum(array_slice($digits, 0, 10)) % 10 !== $digits[10]) {
            $fail('Geçerli bir T.C. kimlik numarası girin.');
        }
    }
}
