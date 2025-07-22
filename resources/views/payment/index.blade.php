@extends('layouts.app')

@section('template_title')
    Payment
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="container-fluid p-0">
                    <div class="row justify-content-center mb-3">
                        <div class="col-auto">
                            <h5 class="text-center">FICHE SUIVI CLIENT</h5>
                        </div>
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-header bg-white">
                            <div class="row align-items-center">
                                <div class="col-md-10">
                                    <p class="mb-1"><strong>Nom :</strong> <span id="clientName">{{ $clients->name }}</span></p>
                                    <p class="mb-1"><strong>Tel :</strong> <span id="clientPhone">{{ $clients->phone }}</span></p>
                                    <p class="mb-0"><strong>Adresse :</strong> <span id="clientAddress">{{ $clients->adress }}</span></p>
                                </div>
                                <div class="col-md-2 text-end">
                                    <span class="badge bg-white text-dark rounded-1 p-2 d-inline-block" style="border: 1px solid #000;">
                                        *SUIVI RÈGLEMENTS
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-body bg-white">
                            <h5>HISTORIQUE RENDEZ VOUS</h5>
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
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body bg-white">
                            <form method="POST" action="{{ route('create-fichedb') }}" role="form" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="client" value="{{ $clients->id }}">
                                
                                <div class="mb-3">
                                    <label for="objectifs" class="form-label">{{ __('OBJECTIFS') }}</label>
                                    <textarea name="objectifs" class="form-control @error('objectifs') is-invalid @enderror" id="custobjectifs">{{ old('objectifs', $fiche?->objectifs) }}</textarea>
                                    {!! $errors->first('objectifs', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                                
                                <div class="mb-3">
                                    <label for="indications" class="form-label">{{ __('CONTRE-INDICATIONS') }}</label>
                                    <textarea name="indications" class="form-control @error('indications') is-invalid @enderror" id="custindications">{{ old('indications', $fiche?->indications) }}</textarea>
                                    {!! $errors->first('indications', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                                
                                <div class="mb-3">
                                    <label for="observations" class="form-label">{{ __('OBSERVATIONS') }}</label>
                                    <textarea name="observations" class="form-control @error('observations') is-invalid @enderror" id="custobservations">{{ old('observations', $fiche?->observations) }}</textarea>
                                    {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                        </div>
                    </div>
                    
                    <!-- Commentaires Rendez-vous -->
                    <div class="card mb-4">
                        <div class="card-body bg-white">
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
                                <button type="submit" class="btn btn-primary btn-lg">{{ __('Valider') }}</button>
                            </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Détails Paiement -->
                    <div class="card">
                        <div class="card-body bg-white">
                            <h5>*DETAILS DU PAYEMENT</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead small">
                                        <tr>
                                            <th>Fomrule/Prestation</th>
                                            <th>Date et Lieu du rdv</th>
                                            <th>Type de payement</th>
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