<?php

namespace App\Support;

class SensitiveData
{
    public static function cpf(?string $cpf, string $placeholder = '-'): string
    {
        $digits = preg_replace('/\D/', '', (string) $cpf);

        if (strlen($digits) !== 11) {
            return $cpf ? '***' : $placeholder;
        }

        return substr($digits, 0, 3).'.***.***-'.substr($digits, 9, 2);
    }

    public static function email(?string $email, string $placeholder = '-'): string
    {
        if (! $email) {
            return $placeholder;
        }

        [$name, $domain] = array_pad(explode('@', mb_strtolower($email), 2), 2, '');

        if (! $domain) {
            return mb_substr($name, 0, 2).'***';
        }

        $domainSuffix = mb_strpos($domain, '.') !== false
            ? mb_substr($domain, (int) mb_strpos($domain, '.'))
            : '';

        return mb_substr($name, 0, 2).'***@***'.$domainSuffix;
    }

    public static function phone(?string $phone, string $placeholder = '-'): string
    {
        $digits = preg_replace('/\D/', '', (string) $phone);

        if (strlen($digits) < 8) {
            return $phone ? '***' : $placeholder;
        }

        return substr($digits, 0, 2).'*****'.substr($digits, -4);
    }
}
