@extends('layouts.app')

@section('title', 'Commande confirmée — Chronolette')

@section('content')
<div class="container">
    <div style="text-align:center;padding:50px 20px;">
        <div style="width:80px;height:80px;border-radius:50%;background:#e6f4ea;color:#137333;font-size:44px;line-height:80px;margin:0 auto 20px;">✓</div>
        <h1 style="color:#232f3e;">Merci pour votre commande !</h1>
        <p style="font-size:16px;color:#555;">Votre commande <strong>{{ $order->order_number }}</strong> a bien été enregistrée.</p>
        <p style="font-size:14px;color:#777;">Notre équipe vous contactera très vite au <strong>{{ $order->phone }}</strong> pour confirmer la livraison à <strong>{{ $order->city }}</strong>.</p>

        <div style="max-width:520px;margin:30px auto 0;border:1px solid #eee;border-radius:10px;padding:20px;text-align:left;">
            <h3 style="margin:0 0 14px;">Récapitulatif</h3>
            @foreach($order->items as $item)
                <div style="display:flex;justify-content:space-between;font-size:14px;padding:6px 0;border-bottom:1px solid #f0f0f0;">
                    <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                    <span>{{ format_mad($item->price * $item->quantity) }}</span>
                </div>
            @endforeach
            <div style="display:flex;justify-content:space-between;font-weight:700;font-size:17px;padding-top:12px;">
                <span>Total</span><span>{{ format_mad($order->total) }}</span>
            </div>
            <div style="font-size:13px;color:#666;margin-top:10px;">
                {{ $order->customer_name }} — {{ $order->phone }} — {{ $order->city }} — {{ $order->address }}
            </div>
        </div>

        <div style="margin-top:26px;max-width:520px;margin-left:auto;margin-right:auto;border:1px solid #d4edda;background:#eefaf0;border-radius:10px;padding:16px;">
            <div style="font-size:14px;color:#14532d;margin-bottom:10px;">
                <i class="bi bi-whatsapp"></i> <strong>Pour finaliser votre commande, envoyez-la au vendeur sur WhatsApp</strong> — le message est déjà prêt, il suffit de confirmer.
            </div>
            <a href="{{ session('new_order_wa_link', whatsapp_link(wa_order_message($order), admin_whatsapp_number())) }}" target="_blank" rel="noopener" class="btn btn-whatsapp w-100">Envoyer ma commande via WhatsApp</a>
        </div>

        <div style="margin-top:20px;">
            <a href="{{ route('home') }}" class="btn btn-dark">Retour à la boutique</a>
        </div>
    </div>
</div>
@endsection
