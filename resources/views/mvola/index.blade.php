@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html>
<head>
    <title>Test Paiement MVola</title>
</head>
<body>
    <h2>Paiement MVola (Test)</h2>
    <form method="POST" action="{{ url('/mvola-test/pay') }}">
        @csrf
        <label>Numéro émetteur (debitParty)</label><br>
        <input type="text" name="debit" value="0343500003"><br><br>

        <label>Numéro récepteur (creditParty)</label><br>
        <input type="text" name="client_number" value="0343500004"><br><br>

        <label>Montant</label><br>
        <input type="text" name="amount" value="1000"><br><br>

        <button type="submit">Tester Paiement</button>
    </form>
</body>
</html>
@endsection
