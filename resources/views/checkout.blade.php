@extends('layouts.app')

@section('title', 'Commander — Chronolette')

@section('content')
<div class="container">
    <h1 style="font-size:26px;color:#232f3e;margin:24px 0;">Finaliser ma commande</h1>

    <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:30px;" class="checkout-layout">
        <div style="border:1px solid #eee;border-radius:10px;padding:24px;">
            <h3 style="margin:0 0 18px;">Informations de livraison</h3>
            <form action="{{ route('order.store') }}" method="POST">
                @csrf
                <div style="margin-bottom:14px;">
                    <label style="font-size:14px;font-weight:600;">Nom et prénom *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required class="field">
                    @error('customer_name') <small style="color:#c5221f;">{{ $message }}</small> @enderror
                </div>
                <div style="margin-bottom:14px;">
                    <label style="font-size:14px;font-weight:600;">Numéro de téléphone *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="06XXXXXXXX" class="field">
                    @error('phone') <small style="color:#c5221f;">{{ $message }}</small> @enderror
                </div>
                <div style="margin-bottom:14px;">
                    <label style="font-size:14px;font-weight:600;">Ville *</label>
                    <input type="text" name="city" value="{{ old('city') }}" required class="field">
                    @error('city') <small style="color:#c5221f;">{{ $message }}</small> @enderror
                </div>
                <div style="margin-bottom:14px;">
                    <label style="font-size:14px;font-weight:600;">Adresse de livraison *</label>
                    <input type="text" name="address" value="{{ old('address') }}" required class="field">
                    @error('address') <small style="color:#c5221f;">{{ $message }}</small> @enderror
                </div>
                <div style="margin-bottom:14px;">
                    <label style="font-size:14px;font-weight:600;">Remarque (facultatif)</label>
                    <textarea name="note" rows="3" class="field">{{ old('note') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;font-size:16px;padding:14px;">Confirmer la commande ({{ format_mad($total) }})</button>
            </form>
        </div>

        <div style="border:1px solid #eee;border-radius:10px;padding:20px;align-self:start;">
            <h3 style="margin:0 0 14px;">Votre commande</h3>
            @foreach($items as $item)
                <div style="display:flex;justify-content:space-between;font-size:14px;padding:8px 0;border-bottom:1px solid #f0f0f0;">
                    <span>{{ $item['product']->name }} × {{ $item['quantity'] }}</span>
                    <span>{{ format_mad($item['line_total']) }}</span>
                </div>
            @endforeach
            <div style="display:flex;justify-content:space-between;font-size:18px;font-weight:700;padding-top:14px;">
                <span>Total</span><span>{{ format_mad($total) }}</span>
            </div>
            <div style="font-size:13px;color:#666;margin-top:8px;">✓ Paiement à la livraison</div>
        </div>
    </div>
</div>

<style>
    .field { width:100%; padding:11px 14px; border:1px solid #ccc; border-radius:6px; font-size:15px; margin-top:4px; box-sizing:border-box; }
    @media (max-width: 900px) { .checkout-layout { grid-template-columns: 1fr !important; } }
    @media (max-width: 640px) {
        .checkout-layout > div { padding: 16px !important; }
    }
</style>
@endsection
