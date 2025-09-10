<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\appointments;
use App\Models\Clients;
use App\Models\FicheClient;
use App\Services\UtilService;
use App\Http\Requests\PaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
 * Class PaymentController
 * @package App\Http\Controllers
 */
class PaymentdbController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index(Request $request)
    {
        $payments = Payment::where('client_id', $request['id'])->paginate();
        $clients = Clients::find($request['id']);
        $appointments = Clients::getAppointmentsByClient($request['id']);
        $fiche=FicheClient::where(['client_id' => $request['id']])->first();
        $gender = UtilService::getGenders();
        $paymentsClients=Clients::getPaymentsByClient($request['id']);
        $appointsCommentaire = Clients::getAppointmentsByClientComment($request['id']);

        return view('payment.index', compact('payments','clients','appointments','fiche','appointsCommentaire','paymentsClients','gender'))
            ->with('i', (request()->input('page', 1) - 1) * $payments->perPage());
    }

    public function createfiche(Request $request, FicheClient $fiche, Clients $client)
    {

        $alldata=$request->all();
        // dd($alldata);
        if(isset($alldata['assistant_comment'])){
                foreach ($alldata['assistant_comment'] as $appointmentId => $commentaire) {
                appointments::changeComment($appointmentId, $commentaire);
            }
        }
        if(isset($alldata['size']) || isset($alldata['weight']) || isset($alldata['IMC'])){
            $reponse= $client->updateclient($alldata['client'], $alldata['weight'], $alldata['gender'], $alldata['size'], $alldata['IMC']);
            echo ($reponse);
            // die();

        }
        $fiche->createFiche($alldata['client'],$alldata['objectifs'], $alldata['indications'], $alldata['consultations'],
                            $alldata['programmes']);
        return redirect()->route('fichedb', ['id' => $alldata['client']])
            ->with('success', 'Fiche client créée');
    }

    public function payment(Request $request)
    {
        $query = Payment::with('client')->orderBy('created_at', 'desc');
        if ($request->filled('phone')) {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where('phone', 'like', '%' . $request->phone . '%');
            });
        }

        if ($request->filled('client')) {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->client . '%');
            });
        }

        if ($request->filled('date_start')) {
            $query->whereDate('paid_at', '>=', $request->date_start);
        }

        if ($request->filled('date_end')) {
            $query->whereDate('paid_at', '<=', $request->date_end);
        }

        $payments = $query->paginate(10);

        $total = $query->clone()->sum('total_amount');
        $nombrePaiements = $query->clone()->count();
        $nombreClients = $query->clone()->distinct('client_id')->count('client_id');

        return view('payment.payment', compact('payments', 'total', 'nombrePaiements', 'nombreClients'))
            ->with('i', (request()->input('page', 1) - 1) * $payments->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $payment = new Payment();
        return view('payment.create', compact('payment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentRequest $request)
    {
        Payment::create($request->validated());

        return redirect()->route('payments.index')
            ->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $payment = Payment::find($id);

        return view('payment.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $payment = Payment::find($id);

        return view('payment.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully');
    }

    public function destroy($id)
    {
        Payment::find($id)->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully');
    }
}
