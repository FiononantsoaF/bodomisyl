@extends('layouts.app')

@section('template_title')
    Payment
@endsection

@section('content')
    <div class="card shadow-xs mb-3 py-3 g-1 p-1 ">
        <div class="row">
            <div class="col-sm-12">
                <div class="container-fluid ">
                    <div class="row align-items-center">
                        <div class="col-md-10">
                            <h5 class="mb-1" style="padding-left: 0.9rem;">FICHE SUIVI CLIENT</h5>
                        </div>
                    </div>       
                    <div class="card mb-3 border-0">
                        <div class="card-header bg-white">
                            <div class="row">
                                <div class="col-md-10">
                                    <table class="table table-borderless table-sm mb-0 small">
                                        <tbody calss="small">
                                            <tr>
                                                <th style="width: 120px;">Nom </th>
                                                <td>{{ $clients->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email </th>
                                                <td>{{ $clients->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tel </th>
                                                <td>{{ $clients->phone }}</td>
                                            </tr>
                                            <tr>
                                                <th>Adresse </th>
                                                <td>{{ $clients->address }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p class="mt-3 mb-0"><strong>*SUIVI RÈGLEMENTS</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body bg-white">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead small">
                                        <tr>
                                            <th>Dates</th>
                                            <th>Formule</th>
                                            <th>Prestation</th>
                                            <th>Nombre de rdv</th>
                                            <th>Nombre de rdv restant</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">
                                        @foreach ($appointments as $appoint)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($appoint->date)->format('d/m/Y') }}</td>
                                                <td>{{ $appoint->prestation }}</td>
                                                <td>{{ $appoint->formule }}</td>
                                                <td>{{ $appoint->nb_rdv }}</td>
                                                <td>{{ $appoint->nb_restant }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <p>HISTORIQUE RENDEZ VOUS</p>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body bg-white ">
                            <form method="POST" action="{{ route('create-fichedb') }}" role="form" enctype="multipart/form-data" class="small">
                                @csrf
                                <input type="hidden" name="client" value="{{ $clients->id }}">
                                
                                <div class="mb-3">
                                    <label for="objectifs" class="form-label">{{ __('OBJECTIFS') }}</label>
                                    <textarea name="objectifs" class="form-control @error('objectifs') is-invalid @enderror" id="custobjectifs">{{ old('objectifs', $fiche?->objectifs) }}</textarea>
                                    {!! $errors->first('objectifs', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                                
                                <div class="mb-3">
                                    <label for="indications" class="form-label">{{ __('CONTRE-INDICATIONS') }}   (ANTECEDENTS & LIMITATIONS)</label>
                                    <textarea name="indications" class="form-control @error('indications') is-invalid @enderror" id="custindications">{{ old('indications', $fiche?->indications) }}</textarea>
                                    {!! $errors->first('indications', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                </div>                          
                                <div class="mb-3">
                                    <label for="observations" class="form-label">{{ __('OBSERVATIONS') }}</label><br>
                                    <div class="row mb-2 small">
                                        <label class="col-sm-2 col-form-label text-end">Sexe :</label>
                                        <div class="col-sm-10">
                                            <select name="gender" class="form-control small" required>
                                                <option value="">--Choisir le genre--</option>
                                            @foreach($gender as $key => $value)
                                                <option value="{{ $key }}" {{ old('gender', $clients->gender ?? '') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-2 small">
                                        <label class="col-sm-2 col-form-label text-end">Taille (m) :</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="size" class="form-control" min="0" step="0.01" value="{{ old('size', $clients?->size) }}">
                                        </div>
                                    </div>

                                    <div class="row mb-2 small">
                                        <label class="col-sm-2 col-form-label text-end">Poids (kg):</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="weight" class="form-control" min="0" step="0.01" value="{{ old('weight', $clients?->weight) }}">
                                        </div>
                                    </div>

                                    <div class="row mb-2 small">
                                        <label class="col-sm-2 col-form-label text-end">IMC :</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="IMC"  class="form-control" min="0" step="0.01" value="{{ old('IMC', $clients?->IMC) }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="mb-3">
                                    <label for="consultations" class="form-label">{{ __('CONSULTATION') }}</label>
                                    <textarea name="consultations" class="form-control @error('indications') is-invalid @enderror" id="custconsultations">{{ old('consultations', $fiche?->consultations) }}</textarea>
                                    {!! $errors->first('consultations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                </div> 
                                <div class="mb-3">
                                    <label for="programmes" class="form-label">{{ __('PRÉCONISATIONS/PROGRAMME') }}</label>
                                    <textarea name="programmes" class="form-control @error('programmes') is-invalid @enderror" id="custprogramme">{{ old('programmes', $fiche?->programmes) }}</textarea>
                                    {!! $errors->first('programmes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                </div> 
                                

                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body bg-white ">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead small">
                                        <tr>
                                            <th>Date rdv</th>
                                            <th>Commentaires</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">
                                        @foreach ($appointsCommentaire as $index => $comment)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($comment->date)->format('d/m/Y') }}</td>
                                                <td>
                                                    {{ $comment->prestation }}
                                                    <input type="hidden" name="appointment_id[]" value="{{ $comment->id }}">
                                                    <textarea name="assistant_comment[{{ $comment->id }}]" 
                                                        class="form-control @error('assistant_comment.' . $comment->id) is-invalid @enderror">{{ old("assistant_comment.{$comment->id}", $comment->commentaire ?? '') }}</textarea>

                                                    @error("assistant_comment.{$comment->id}")
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg btn-sm">{{ __('Valider') }}</button>
                            </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Détails Paiement -->
                    <div class="card">
                        <div class="card-body bg-white ">
                            <h5>*DETAILS DU PAYEMENT</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead small">
                                        <tr>
                                            <th>Formule/Prestation</th>
                                            <th>Date et Lieu du rdv</th>
                                            <th>Type de paiement</th>
                                            <th>Montant (En Ariary)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">
                                        @foreach ($paymentsClients as $pay)
                                            <tr>
                                                <td>{{ $pay->prestation }} / {{ $pay->formule }}</td>
                                                <td>{{ \Carbon\Carbon::parse($pay->date_de_paiement)->format('d/m/Y') }}</td>
                                                <td>{{ $pay->methodes_utilisees }}</td>
                                                <td>{{ $pay->total_paye }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                {!! $payments->links() !!}
            </div>
        </div>
    </div>
@endsection