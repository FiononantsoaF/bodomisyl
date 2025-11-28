<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use Illuminate\Http\Request;
use App\Models\PasswordResetTokens;

class ClientsController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/client/login",
     *     summary="Login client",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="login", type="string",description="email ou phone"),
     *             @OA\Property(property="password", type="string",description="mot de passe")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
    */
    public function loginclient(Request $request)
    {
        $param = $request->all();
        $checkclient = Clients::orWhere(["email"=>$param['login'],"phone"=>$param['login']])->first();
        // $pass = password_hash("rasmuslerdorf", PASSWORD_DEFAULT);
        if($checkclient){
            
            
            // print_r("-----".password_hash($param['password'], PASSWORD_DEFAULT));

            /*$password = $param['password'];
            $hash = $checkclient->password;

            // $algorithm = PASSWORD_BCRYPT;
            $algorithm = PASSWORD_DEFAULT;
            // bcrypt's cost parameter can change over time as hardware improves
            // $options = ['cost' => 13];

            // Verify stored hash against plain-text password
            
            if (password_needs_rehash($hash, $algorithm)) {
                // If so, create a new hash, and replace the old one
                $newHash = password_hash($password, $algorithm);
            }
            var_dump(password_needs_rehash($hash, $algorithm));die();

            print_r($checkclient->password);
            // print_r("------aaaa-------".$newHash);
            var_dump("-----".$param['password']);*/
                
            $verifpass = password_verify($param['password'], $checkclient->password);



            if($verifpass){
                return $this->apiResponse(true, "Login avec succès",$checkclient, 200);
            }else{
                return $this->apiResponse(false, "Erreur mot de passe",null, 400);
            }
        }else{
            return $this->apiResponse(false, "Erreur login",null, 400);
        }
        

        
    }

    /**
     * @OA\Post(
     *     path="/api/client/changepass",
     *     summary="Changer mot de passe",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string",description="Token"),
     *             @OA\Property(property="newPassword", type="string",description="mot de passe")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
    */

    public function changepassword(Request $request)
    {
        $param = $request->all();
        $request->validate([
            'token' => 'required|string',
            'newPassword' => 'required|string',
        ]);
        $reset = PasswordResetTokens::where('token', $request->token)->first();
        if (!$reset) {
            return $this->apiResponse(false, "Lien expiré ou invalide", null, 400);
        }
        $client = Clients::find($reset->id_client);
        if (!$client) {
            return $this->apiResponse(false, "Utilisateur non trouvé", null, 404);
        }
        $client->update(["password"=>password_hash($param['newPassword'], PASSWORD_DEFAULT)]);
        $reset->delete();
        return $this->apiResponse(true, "Modification mot de passe avec succès",$client, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/user/update",
     *     summary="Changer les informations de l'utilisateur",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="number", description="ID de l'utilisateur"),
     *             @OA\Property(property="name", type="string", description="Nom complet"),
     *             @OA\Property(property="phone", type="string", description="Numéro de téléphone"),
     *             @OA\Property(property="email", type="string", description="Email"),
     *             @OA\Property(property="address", type="string", description="Adresse"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Modification réussie"),
     *     @OA\Response(response=400, description="Erreur de validation ou ID manquant")
     * )
     */
    public function update(Request $request)
    {
        $param = $request->all();
        if (!isset($param['id'])) {
            return $this->apiResponse(true, "Id utilisateur manquant", null, 400);
        }
        $client = Clients::find($param['id']);
        if (!$client) {
            return $this->apiResponse(true, "Utilisateur non trouvé", null, 404);
        }
        $client->name = $param['name'] ?? $client->name;
        $client->phone = $param['phone'] ?? $client->phone;
        $client->email = $param['email'] ?? $client->email;
        $client->address = $param['address'] ?? $client->address;
        $client->save();
        return $this->apiResponse(true, "Informations mises à jour avec succès", $client, 200);
    }


    /**
     * @OA\Get(
     *     path="/api/check-email/",
     *     summary="Vérification email saisi ",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         description="email à vérifier",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $email = $request->query('email');
        $exists = \App\Models\Clients::where('email', $email)->exists();
        return response()->json([
            'exists' => $exists
        ]);
    }

    
    /**
     * @OA\Get(
     *     path="/api/getClientById",
     *     summary="Récupérer un client par son ID",
     *     @OA\Parameter(
     *         name="client_id",
     *         in="query",
     *         required=true,
     *         description="ID du client à récupérer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Client trouvé"),
     *     @OA\Response(response="404", description="Client non trouvé")
     * )
     */
    public function getClientById(Request $request)
    {
        $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
        ]);
        $id = $request->query('client_id');
        $client = \App\Models\Clients::find($id);
        if ($client) {
            return $this->apiResponse(true, "Client trouvé", $client, 200);
        }
        return $this->apiResponse(false, "Pas de client", null, 404);
    }

    /**
     * @OA\Post(
     *     path="/api/check-password",
     *     summary="Vérifie si un mot de passe correspond à celui du client",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="email@gmail.com"),
     *             @OA\Property(property="password", type="string", example="Secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mot de passe correct",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Client non trouvé"),
     *     @OA\Response(response=401, description="Mot de passe incorrect")
     * )
     */
    public function checkPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $client = \App\Models\Clients::where("email",$request->email)->first();
        if (!$client) {
            return $this->apiResponse(true, "nouveau client", null, 200);
        }
        if (!\Illuminate\Support\Facades\Hash::check($request->password, $client->password)) {
            return $this->apiResponse(false, "Mot de passe incorrect", false, 401);
        }

        return $this->apiResponse(true, "Mot de passe valide", true, 200);
    }

    private function apiResponse($success, $message, $data = null, $status = 200) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Clients $clients)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clients $clients)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clients $clients)
    {
        //
    }
}
