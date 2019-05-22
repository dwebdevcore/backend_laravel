<?php

namespace App\Transformers;

class PhoneTransformer
{
    public function toE164(string $phone)
    {
        $phone = preg_replace('/[^0-9\+]+/', '', $phone);

        if ($phone[0] !== '+') {
            $phone = '+' . $phone;
        }

        return $phone;
    }
}
