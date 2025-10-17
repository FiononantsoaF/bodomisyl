<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ClientsFiles;
use Carbon\Carbon;
use DateTime;
use App\Services\GoogleDriveService;

class ClientsFilesApiController extends Controller
{
    protected $googleDriveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->googleDriveService = $driveService;
    }

    /**
     * @OA\Get(
     *     path="/api/clientsfiles/{client_id}",
     *     summary="Vérification des fichiers clients pour un client spécifique",
     *     @OA\Parameter(
     *         name="client_id",
     *         in="path",
     *         required=true,
     *         description="ID du client",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function show($client_id)
    {
        $clientFiles = ClientsFiles::getByClientWithActiveSubscription($client_id);
        if ($clientFiles->isEmpty()) {
            $success = true;
            $message = "Aucun fichier trouvé pour ce client avec une souscription active.";
            $data = null;

        } else {
            $success = true;
            $message = "Fichiers récupérés avec succès.";
            $data = $clientFiles;
        }
        $clientFile = ClientsFiles::where('client_id', $client_id)->first();
        if ($clientFile) {
            $privateLink = $this->googleDriveService->getPrivateLink($clientFile->file_path);

            return response()->json([
                'success' => true,
                'link' => $privateLink
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Aucun fichier disponible pour ce client.'
            ]);
        }


        return $this->apiResponse($success, $message, $data, 200);
    }


    private function apiResponse($success, $message, $data = null, $status = 200) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
