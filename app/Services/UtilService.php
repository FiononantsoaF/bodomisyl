<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\CarteCadeauClient;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTime;

class UtilService
{

    public static function getGenders(): array
    {
        return [
            'Homme' => 'Homme',
            'Femme' => 'Femme',
        ];
    }

    public static function generateUniqueCode($prefix = 'DOM', $digits = 6, $suffixDigits = 3, $maxAttempts = 10)
    {
        $attempt = 0;
        do {
            $number = str_pad((string) mt_rand(0, (int) str_repeat('9', $digits)), $digits, '0', STR_PAD_LEFT);
            $suffix = 'KD' . str_pad((string) mt_rand(0, (int) str_repeat('9', $suffixDigits)), $suffixDigits, '0', STR_PAD_LEFT);
            $code = "{$prefix}-{$number}{$suffix}";
            $exists = DB::table('carte_cadeau_client')->where('code', $code)->exists();
            $attempt++;
        } while ($exists && $attempt < $maxAttempts);

        if ($exists) {
            throw new \Exception("Impossible de générer un code unique après {$maxAttempts} tentatives.");
        }

        return $code;
    }

        


}