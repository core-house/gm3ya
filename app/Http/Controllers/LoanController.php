<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Client;
use App\Models\Safe;
use App\Models\Transaction;
use App\Models\Receipt;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['client', 'safe'])->orderBy('created_at', 'desc')->get();
        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $safes = Safe::all();
        return view('loans.create', compact('clients', 'safes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'safe_id' => 'required|exists:safes,id',
            'amount' => 'required|numeric|min:0',
            'loan_date' => 'required|date',
        ]);

        $loan = Loan::create($request->all());

        // خصم من الصندوق
        $safe = Safe::find($request->safe_id);
        $safe->balance -= $request->amount;
        $safe->save();

        // تسجيل في الحركات
        Transaction::create([
            'safe_id' => $request->safe_id,
            'type' => 'out',
            'amount' => $request->amount,
            'description' => 'سلفة للعميل: ' . $loan->client->name,
            'transaction_date' => $request->loan_date,
        ]);

        // تسجيل إيصال
        Receipt::create([
            'client_id' => $request->client_id,
            'safe_id' => $request->safe_id,
            'amount' => $request->amount,
            'type' => 'loan',
            'reference_id' => $loan->id,
            'receipt_date' => $request->loan_date,
            'notes' => 'سلفة',
        ]);

        return redirect()->route('loans.index')->with('success', 'تم إضافة السلفة');
    }

    public function show(Loan $loan)
    {
        $loan->load(['client', 'safe', 'payments']);
        return view('loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        $clients = Client::where('status', 'active')->get();
        $safes = Safe::all();
        return view('loans.edit', compact('loan', 'clients', 'safes'));
    }

    public function update(Request $request, Loan $loan)
    {
        $request->validate([
            'status' => 'required|in:active,paid',
        ]);

        $loan->update($request->only('status'));
        return redirect()->route('loans.index')->with('success', 'تم تحديث السلفة');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'تم حذف السلفة');
    }
}
