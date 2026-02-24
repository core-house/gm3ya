<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Client;
use App\Models\Safe;
use App\Models\Transaction;
use App\Models\Receipt;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index()
    {
        $deposits = Deposit::with(['client', 'safe'])->orderBy('created_at', 'desc')->get();
        return view('deposits.index', compact('deposits'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $safes = Safe::all();
        return view('deposits.create', compact('clients', 'safes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'safe_id' => 'required|exists:safes,id',
            'amount' => 'required|numeric|min:0',
            'deposit_date' => 'required|date',
        ]);

        $deposit = Deposit::create($request->all());

        // إضافة للصندوق
        $safe = Safe::find($request->safe_id);
        $safe->balance += $request->amount;
        $safe->save();

        // تسجيل في الحركات
        Transaction::create([
            'safe_id' => $request->safe_id,
            'type' => 'in',
            'amount' => $request->amount,
            'description' => 'أمانة من العميل: ' . $deposit->client->name,
            'transaction_date' => $request->deposit_date,
        ]);

        // تسجيل إيصال
        Receipt::create([
            'client_id' => $request->client_id,
            'safe_id' => $request->safe_id,
            'amount' => $request->amount,
            'type' => 'deposit',
            'reference_id' => $deposit->id,
            'receipt_date' => $request->deposit_date,
            'notes' => 'أمانة',
        ]);

        return redirect()->route('deposits.index')->with('success', 'تم إضافة الأمانة');
    }

    public function show(Deposit $deposit)
    {
        $deposit->load(['client', 'safe']);
        return view('deposits.show', compact('deposit'));
    }

    public function edit(Deposit $deposit)
    {
        $clients = Client::where('status', 'active')->get();
        $safes = Safe::all();
        return view('deposits.edit', compact('deposit', 'clients', 'safes'));
    }

    public function update(Request $request, Deposit $deposit)
    {
        $request->validate([
            'status' => 'required|in:active,returned',
        ]);

        $deposit->update($request->only('status'));
        return redirect()->route('deposits.index')->with('success', 'تم تحديث الأمانة');
    }

    public function destroy(Deposit $deposit)
    {
        $deposit->delete();
        return redirect()->route('deposits.index')->with('success', 'تم حذف الأمانة');
    }
}
