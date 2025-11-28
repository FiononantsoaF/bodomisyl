<?php

namespace App\Http\Controllers;

use App\Models\CarteCadeauClient;
use App\Models\CarteCadeauService;
use App\Http\Requests\CarteCadeauClientRequest;
use App\Models\Services;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\CarteCadeauMail;
use Mail;


/**
 * Class CarteCadeauClientController
 * @package App\Http\Controllers
 */
class CarteCadeauClientdbController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index()
    {
        $carteCadeauClients = CarteCadeauClient::with([
            'clients',
            'carteCadeauService.service.serviceCategory'],
            'appointments',
            'subscriptions'
        )->paginate(20);
        $activemenuccs = 1;
        // dd($carteCadeauClients);
        return view('carte-cadeau-client.index', compact('carteCadeauClients','activemenuccs'))
            ->with('i', (request()->input('page', 1) - 1) * $carteCadeauClients->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $carteCadeauClient = new CarteCadeauClient();
        return view('carte-cadeau-client.create', compact('carteCadeauClient'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarteCadeauClientRequest $request)
    {
        CarteCadeauClient::create($request->validated());

        return redirect()->route('carte-cadeau-clients.index')
            ->with('success', 'CarteCadeauClient created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $carteCadeauClient = CarteCadeauClient::find($id);
        
        return view('carte-cadeau-client.show', compact('carteCadeauClient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $carteCadeauClient = CarteCadeauClient::find($id);

        return view('carte-cadeau-client.edit', compact('carteCadeauClient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarteCadeauClientRequest $request, CarteCadeauClient $carteCadeauClient)
    {
        $carteCadeauClient->update($request->validated());

        return redirect()->route('carte-cadeau-clients.index')
            ->with('success', 'CarteCadeauClient updated successfully');
    }

    public function destroy($id)
    {
        CarteCadeauClient::find($id)->delete();

        return redirect()->route('carte-cadeau-clients.index')
            ->with('success', 'CarteCadeauClient deleted successfully');
    }

    public function payercadeau(Request $request)
    {
        $carte = CarteCadeauClient::where("code", $request->code)->first();
        if (!$carte) {
            return redirect()->back()->with('error', 'Code introuvable.');
        }
        $carte->is_paid = 1;
        $carte->save();
        Payment::create([
            'client_id'       => $request->client_id,
            'total_amount'    => $request->total_amount,
            'deposit'         => $request->total_amount,
            'method'          => $request->method,
            'code_carte_cadeau_client' =>$request->code,
            'status'          => 'paid',
            'paid_at'         => Carbon::now(),
        ]);
        Mail::to($carte->clients->email)->send(new CarteCadeauMail($carte));
        return redirect()->back()->with('success', 'Paiement enregistré');
    }

    public function pdf_carte($id)
    {
        $cadeau = CarteCadeauClient::with([
            'carteCadeauService.service.serviceCategory',
            'clients'
        ])->find($id);
        if (!$cadeau) {
            return redirect()->back()->with('error', 'Bon cadeau introuvable.');
        }
        return view('carte-cadeau-client.pdf-carte', [
            'cadeau' => $cadeau
        ]);
    }

    public function downloadPdf($id)
    {
        $cadeau = CarteCadeauClient::with(['carteCadeauService.service.serviceCategory', 'clients'])
            ->find($id);
        $pdf = Pdf::loadView('carte-cadeau-client.pdf', compact('cadeau'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultFont' => 'Arial',
                'isPdf' => true, 
                'dpi' => 150,
                'enable_php' => false
            ]);
        $fileName = 'bon-cadeau-' . $cadeau->code . '.pdf';
        return $pdf->download($fileName);
    }


}
