<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\NotifyStudio;
use App\Models\Inquiry;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['status' => 'nullable|in:received,reviewing,confirmed,completed,cancelled']);

        return Inertia::render('admin/Orders', ['orders' => Order::select(['id', 'reference', 'status', 'customer_name', 'created_at', 'notification_status'])->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))->latest()->paginate(20)->withQueryString(), 'status' => $request->query('status', '')]);
    }

    public function show(Order $order)
    {
        return Inertia::render('admin/Order', ['order' => $order->load('items')]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate(['status' => 'required|in:received,reviewing,confirmed,completed,cancelled', 'admin_notes' => 'nullable|string|max:8000']);
        $order->update($data);

        return back()->with('success', 'Solicitud actualizada. La disponibilidad de la obra se gestiona desde su ficha.');
    }

    public function retry(Order $order)
    {
        if ($order->notification_status !== 'sent') {
            $order->update(['notification_status' => 'pending']);
            NotifyStudio::dispatch('order', $order->id)->onConnection('database');
        }

        return back()->with('success', 'Aviso programado para envío.');
    }

    public function inquiries()
    {
        return Inertia::render('admin/Inquiries', ['inquiries' => Inquiry::latest()->paginate(20)]);
    }

    public function read(Inquiry $inquiry)
    {
        $inquiry->update(['read_at' => now()]);

        return back()->with('success', 'Consulta marcada como leída.');
    }
}
