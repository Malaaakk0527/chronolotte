<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    private function query(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->has('status') && in_array($request->status, Order::STATUSES)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function index(Request $request)
    {
        $orders = $this->query($request)->paginate(20)->withQueryString();
        $statuses = Order::STATUSES;

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function export(Request $request): StreamedResponse
    {
        $statusesLabels = [
            'nouvelle' => 'Nouvelle',
            'confirmee' => 'Confirmée',
            'expediee' => 'Expédiée',
            'livree' => 'Livrée',
            'annulee' => 'Annulée',
        ];

        $orders = $this->query($request)->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="commandes-'.now()->format('Y-m-d').'.csv"',
        ];

        $callback = function () use ($orders, $statusesLabels) {
            $fh = fopen('php://output', 'w');

            fwrite($fh, "\xEF\xBB\xBF");
            fputcsv($fh, ['N° commande', 'Date', 'Client', 'Téléphone', 'Ville', 'Adresse', 'Articles', 'Total (Dh)', 'Statut'], ';');

            foreach ($orders as $order) {
                $articles = $order->items->map(fn ($i) => $i->product_name.' ×'.$i->quantity)->implode(' | ');
                fputcsv($fh, [
                    $order->order_number,
                    $order->created_at->format('d/m/Y H:i'),
                    $order->customer_name,
                    $order->phone,
                    $order->city,
                    $order->address,
                    $articles,
                    number_format((float) $order->total, 2, ',', ' '),
                    $statusesLabels[$order->status] ?? $order->status,
                ], ';');
            }

            fclose($fh);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function show(Order $order)
    {
        $order->load('items.product');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:'.implode(',', Order::STATUSES),
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('success', 'Commande '.$order->order_number.' : statut « '.$order->statusLabel().' ».');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Commande supprimée.');
    }
}
