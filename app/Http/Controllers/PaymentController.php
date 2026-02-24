<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Client;
use App\Models\Safe;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['client', 'safe'])->orderBy('payment_date', 'desc')->get();
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $safes = Safe::all();
        return view('payments.create', compact('clients', 'safes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'safe_id' => 'required|exists:safes,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:association,loan,debt,deposit_return',
            'payment_date' => 'required|date',
        ]);

        Payment::create($request->all());

        // خصم من الصندوق
        $safe = Safe::find($request->safe_id);
        $safe->balance -= $request->amount;
        $safe->save();

        // تسجيل في الحركات
        $client = Client::find($request->client_id);
        Transaction::create([
            'safe_id' => $request->safe_id,
            'type' => 'out',
            'amount' => $request->amount,
            'description' => 'دفعة للعميل: ' . $client->name . ' - ' . $request->type,
            'transaction_date' => $request->payment_date,
        ]);

        return redirect()->route('payments.index')->with('success', 'تم تسجيل الدفع');
    }
}
