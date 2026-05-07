@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="container py-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <!-- En-tête -->
                <div class="card-header bg-white border-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <span class="fw-bold fs-5">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $user->name }}</h3>
                            <span class="badge bg-primary mt-1">
                                @if($user->role === 'client') 👤 Client
                                @elseif($user->role === 'driver') 🚗 Chauffeur
                                @else 👑 Administrateur
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success rounded-3">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Colonne gauche -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nom complet</label>
                                    <input type="text" name="name" class="form-control p-2 rounded-3" value="{{ old('name', $user->name) }}" required>
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email" class="form-control p-2 rounded-3" value="{{ old('email', $user->email) }}" required>
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Téléphone</label>
                                    <input type="text" name="phone_number" class="form-control p-2 rounded-3" value="{{ old('phone_number', $user->phone_number) }}">
                                    @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Colonne droite -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Mot de passe actuel</label>
                                    <input type="password" name="current_password" class="form-control p-2 rounded-3">
                                    @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nouveau mot de passe</label>
                                    <input type="password" name="password" class="form-control p-2 rounded-3">
                                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Confirmer le mot de passe</label>
                                    <input type="password" name="password_confirmation" class="form-control p-2 rounded-3">
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary px-4">Annuler</a>
                            <button type="submit" class="btn btn-premium px-4">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
