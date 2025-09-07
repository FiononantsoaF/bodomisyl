<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
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


}