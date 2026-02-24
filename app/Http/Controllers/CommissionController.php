<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Association;
use App\Models\Safe;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        $commissions = Commission::with(['association', 'safe'])
            ->orderBy('commission_date', 'desc')->get();
        return view('commissions.index', compact('commissions'));
    }

    public function create()
    {
        $associations = Association::where('status', 'active')->get();
        $safes = Safe::all();
        return view('commissions.create', compact('associations', 'safes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'association_id' => 'required|exists:associations,id',
            'safe_id' => 'required|exists:safes,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:percentage,fixed',
            'commission_date' => 'required|date',
        ]);

        $commission = Commission::create($request->all());

        // خصم من الصندوق
        $safe = Safe::find($request->safe_id);
        $safe->balance -= $request->amount;
        $safe->save();

        // تسجيل في الحركات
        Transaction::create([
            'safe_id' => $request->safe_id,
            'type' => 'out',
            'amount' => $request->amount,
            'description' => 'عمولة جمعية: ' . $commission->association->name,
            'transaction_date' => $request->commission_date,
        ]);

        return redirect()->route('commissions.index')->with('success', 'تم تسجيل العمولة');
    }

    public function show(Commission $commission)
    {
        $commission->load(['association', 'safe']);
        return view('commissions.show', compact('commission'));
    }

    public function destroy(Commission $commission)
    {
        $commission->delete();
        return redirect()->route('commissions.index')->with('success', 'تم حذف العمولة');
    }
}
