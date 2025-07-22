<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Http\Requests\PaymentTransactionRequest;

/**
 * Class PaymentTransactionController
 * @package App\Http\Controllers
 */
class PaymentTransactiondbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentTransactions = PaymentTransaction::paginate();

        return view('payment-transaction.index', compact('paymentTransactions'))
            ->with('i', (request()->input('page', 1) - 1) * $paymentTransactions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $paymentTransaction = new PaymentTransaction();
        return view('payment-transaction.create', compact('paymentTransaction'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentTransactionRequest $request)
    {
        PaymentTransaction::create($request->validated());

        return redirect()->route('payment-transactions.index')
            ->with('success', 'PaymentTransaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $paymentTransaction = PaymentTransaction::find($id);

        return view('payment-transaction.show', compact('paymentTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $paymentTransaction = PaymentTransaction::find($id);

        return view('payment-transaction.edit', compact('paymentTransaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentTransactionRequest $request, PaymentTransaction $paymentTransaction)
    {
        $paymentTransaction->update($request->validated());

        return redirect()->route('payment-transactions.index')
            ->with('success', 'PaymentTransaction updated successfully');
    }

    public function destroy($id)
    {
        PaymentTransaction::find($id)->delete();

        return redirect()->route('payment-transactions.index')
            ->with('success', 'PaymentTransaction deleted successfully');
    }
}
