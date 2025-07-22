<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Http\Requests\CurrencyRequest;
use Illuminate\Http\Request;

/**
 * Class CurrencyController
 * @package App\Http\Controllers
 */
class CurrencydbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = Currency::paginate();
        $menuclient = 1;
        return view('currency.index', compact('currencies','menuclient'))
            ->with('i', (request()->input('page', 1) - 1) * $currencies->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currency = new Currency();
        return view('currency.create', compact('currency'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CurrencyRequest $request)
    {
        
        Currency::create($request->validated());

        return redirect()->route('currencydb')
            ->with('success', 'Currency created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $currency = Currency::find($id);

        return view('currency.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $currency = Currency::find($id);

        return view('currency.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $alldata= $request->all();
        Currency::changeCurrency($alldata['id'], $alldata['money'], $alldata['value']);
        return redirect()->route('currencydb')
            ->with('success', 'Mise à jours effectuée');
    }

    public function destroy($id)
    {
        Currency::find($id)->delete();

        return redirect()->route('currencies.index')
            ->with('success', 'Currency deleted successfully');
    }
}
