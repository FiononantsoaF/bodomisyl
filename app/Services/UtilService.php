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

    public static function generateUniqueCode($prefix = 'DOM', $digits = 5, $maxAttempts = 10)
        {
            $attempt = 0;

            do {
                // Génère un nombre aléatoire à 5 chiffres (avec zéros en tête)
                $number = str_pad((string) mt_rand(0, (int) str_repeat('9', $digits)), $digits, '0', STR_PAD_LEFT);
                
                // Crée le code au format DOM-12345
                $code = "{$prefix}-{$number}";
                $exists = DB::table('CarteCadeauClient')->where('code', $code)->exists();
                $attempt++;
            } while ($exists && $attempt < $maxAttempts);
            if ($exists) {
                throw new \Exception("Impossible de générer un code unique après {$maxAttempts} tentatives.");
            }

            return $code;
        }
        


}