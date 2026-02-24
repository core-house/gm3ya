<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Client;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function index()
    {
        $debts = Debt::with('client')->orderBy('due_date', 'asc')->get();
        return view('debts.index', compact('debts'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        return view('debts.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:temporary,permanent',
            'due_date' => 'required|date',
        ]);

        Debt::create($request->all());
        return redirect()->route('debts.index')->with('success', 'تم إضافة الدين');
    }

    public function show(Debt $debt)
    {
        $debt->load(['client', 'payments']);
        return view('debts.show', compact('debt'));
    }

    public function edit(Debt $debt)
    {
        $clients = Client::where('status', 'active')->get();
        return view('debts.edit', compact('debt', 'clients'));
    }

    public function update(Request $request, Debt $debt)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:temporary,permanent',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid',
        ]);

        $debt->update($request->all());
        return redirect()->route('debts.index')->with('success', 'تم تحديث الدين');
    }

    public function destroy(Debt $debt)
    {
        $debt->delete();
        return redirect()->route('debts.index')->with('success', 'تم حذف الدين');
    }
}
