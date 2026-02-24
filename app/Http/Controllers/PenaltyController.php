<?php

namespace App\Http\Controllers;

use App\Models\Penalty;
use App\Models\Client;
use App\Models\Association;
use Illuminate\Http\Request;

class PenaltyController extends Controller
{
    public function index()
    {
        $penalties = Penalty::with(['client', 'association'])
            ->orderBy('penalty_date', 'desc')->get();
        return view('penalties.index', compact('penalties'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $associations = Association::where('status', 'active')->get();
        return view('penalties.create', compact('clients', 'associations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'association_id' => 'nullable|exists:associations,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:late_payment,early_exit,breach,other',
            'reason' => 'required|string',
            'penalty_date' => 'required|date',
        ]);

        Penalty::create($request->all());
        return redirect()->route('penalties.index')->with('success', 'تم تسجيل الغرامة');
    }

    public function show(Penalty $penalty)
    {
        $penalty->load(['client', 'association']);
        return view('penalties.show', compact('penalty'));
    }

    public function edit(Penalty $penalty)
    {
        $clients = Client::where('status', 'active')->get();
        $associations = Association::where('status', 'active')->get();
        return view('penalties.edit', compact('penalty', 'clients', 'associations'));
    }

    public function update(Request $request, Penalty $penalty)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:late_payment,early_exit,breach,other',
            'reason' => 'required|string',
            'status' => 'required|in:pending,paid,waived',
        ]);

        if ($request->status == 'paid' && !$penalty->paid_date) {
            $request->merge(['paid_date' => now()]);
        }

        $penalty->update($request->all());
        return redirect()->route('penalties.index')->with('success', 'تم تحديث الغرامة');
    }

    public function destroy(Penalty $penalty)
    {
        $penalty->delete();
        return redirect()->route('penalties.index')->with('success', 'تم حذف الغرامة');
    }

    public function markPaid(Penalty $penalty)
    {
        $penalty->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);
        return back()->with('success', 'تم تسجيل سداد الغرامة');
    }

    public function waive(Penalty $penalty)
    {
        $penalty->update(['status' => 'waived']);
        return back()->with('success', 'تم إلغاء الغرامة');
    }
}
