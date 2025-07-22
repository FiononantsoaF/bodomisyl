<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use Illuminate\Http\Request;

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
     *    @OA\Parameter(
     *         name="idclient",
     *         in="query"
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="password", type="string",description="mot de passe")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
    */

    public function changepassword(Request $request)
    {
        $param = $request->all();
        $checkclient = Clients::where("id",'=',$param['idclient'])->first();
        if(!$checkclient){
            return $this->apiResponse(false, "Client non trouvé",null, 400);
        }
        $checkclient->update(["password"=>password_hash($param['password'], PASSWORD_DEFAULT)]);
        return $this->apiResponse(true, "Modification login avec succès",$checkclient, 200);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clients $clients)
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
