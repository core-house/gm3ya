<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Client;
use App\Models\Safe;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function index()
    {
        $receipts = Receipt::with(['client', 'safe'])->orderBy('receipt_date', 'desc')->get();
        return view('receipts.index', compact('receipts'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $safes = Safe::all();
        return view('receipts.create', compact('clients', 'safes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'safe_id' => 'required|exists:safes,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:association,loan,deposit',
            'receipt_date' => 'required|date',
        ]);

        Receipt::create($request->all());

        // إضافة للصندوق
        $safe = Safe::find($request->safe_id);
        $safe->balance += $request->amount;
        $safe->save();

        // تسجيل في الحركات
        $client = Client::find($request->client_id);
        Transaction::create([
            'safe_id' => $request->safe_id,
            'type' => 'in',
            'amount' => $request->amount,
            'description' => 'قبض من العميل: ' . $client->name . ' - ' . $request->type,
            'transaction_date' => $request->receipt_date,
        ]);

        return redirect()->route('receipts.index')->with('success', 'تم تسجيل القبض');
    }
}
