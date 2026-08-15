<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Chronolette Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #131921; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #fff; border-radius: 14px; padding: 40px; width: 380px; box-shadow: 0 10px 40px rgba(0,0,0,.4); }
    </style>
</head>
<body>
<div class="login-card">
    <div style="text-align:center;margin-bottom:24px;">
        <img src="{{ asset('images/logo-site.png') }}" alt="CHRONOLETTE" style="max-height:60px;margin-bottom:10px;">
        <h4 style="font-weight:700;">Administration</h4>
        <div style="font-size:13px;color:#888;">Connectez-vous pour gérer la boutique</div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger py-2" style="font-size:13px;">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label" style="font-size:13px;">Nom d'utilisateur</label>
            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label" style="font-size:13px;">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember" style="font-size:13px;">Se souvenir de moi</label>
        </div>
        <button class="btn btn-dark w-100" style="padding:11px;">Se connecter</button>
    </form>

    <div style="text-align:center;margin-top:18px;font-size:12px;color:#999;">
        Retour à la boutique : <a href="{{ route('home') }}" class="text-decoration-none">chronolette</a>
    </div>
</div>
</body>
</html>
