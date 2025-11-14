@extends('layouts.app')

@section('template_title')
    Témoignages clients
@endsection

@section('content')
    <div class="container-fluid small mb-2 py-1 p-0">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-1">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span id="card_title">
                            {{ __('Témoignages clients') }}
                        </span>

                        <!-- Formulaire d'upload -->
                        <form action="{{ route('testimonialdb.store') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                            @csrf
                            <div class="input-group input-group-sm">
                                <input type="file" name="file_path" class="form-control form-control-sm" accept="image/*" required>
                                <button type="submit" class="btn btn-primary btn-sm">Télécharger</button>
                            </div>
                        </form>
                    </div>

                    <!-- Message succès -->
                    @if ($message = Session::get('success'))
                        <div id="alertMessage" class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="row">
                            @foreach ($testimonials as $testimonial)
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="position-relative image-card">
                                        <img src="{{ asset('imageTemoignage/' . $testimonial->file_path) }}" 
                                            alt="Témoignage" 
                                            class="img-thumbnail w-100"
                                            style="height: 200px; object-fit: cover;">

                                        <!-- Boutons flottants -->
                                        <div class="image-options">
                                            <form action="{{ route('testimonialdb.destroy', $testimonial->id) }}" method="POST" onsubmit="return confirm('Supprimer cette image ?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"> <i class="bi bi-trash"></i></button>
                                            </form>

                                            <form action="{{ route('testimonialdb.toggle', $testimonial->id) }}" method="POST" class="d-inline ms-2">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    {{ $testimonial->is_active ? '⛔' : '✔️' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {!! $testimonials->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Style -->
    <style>
        .image-card {
            overflow: hidden;
        }
        .image-options {
            position: absolute;
            top: 5px;
            right: 5px;
            display: none;
        }
        .image-card:hover .image-options {
            display: block;
        }
    </style>
    <script>
        setTimeout(() => {
            const alert = document.getElementById('alertMessage');
            if (alert) alert.style.display = 'none';
        }, 3000);
    </script>
@endsection
