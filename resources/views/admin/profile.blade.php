@extends('admin.layouts.app')

@section('title', 'Mon profil — Chronolette Admin')

@section('content')
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bi bi-person-gear"></i> Mes informations</h5>

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', auth('admin')->user()->name) }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nom d'utilisateur</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username', auth('admin')->user()->username) }}" required>
                        @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <button type="submit" class="btn btn-dark"><i class="bi bi-check-lg"></i> Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bi bi-shield-lock"></i> Changer le mot de passe</h5>

                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Mot de passe actuel</label>
                        <input type="password" name="current_password" class="form-control" required>
                        @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <small class="d-block text-muted mb-3">Minimum 8 caractères.</small>

                    <button type="submit" class="btn btn-dark"><i class="bi bi-key"></i> Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
