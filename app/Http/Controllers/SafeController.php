<?php

namespace App\Http\Controllers;

use App\Models\Safe;
use Illuminate\Http\Request;

class SafeController extends Controller
{
    public function index()
    {
        $safes = Safe::orderBy('created_at', 'desc')->get();
        return view('safes.index', compact('safes'));
    }

    public function create()
    {
        return view('safes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        Safe::create($request->all());
        return redirect()->route('safes.index')->with('success', 'تم إضافة الصندوق');
    }

    public function show(Safe $safe)
    {
        $safe->load(['transactions' => function($q) {
            $q->orderBy('transaction_date', 'desc')->limit(50);
        }]);
        return view('safes.show', compact('safe'));
    }

    public function edit(Safe $safe)
    {
        return view('safes.edit', compact('safe'));
    }

    public function update(Request $request, Safe $safe)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $safe->update($request->only(['name', 'description']));
        return redirect()->route('safes.index')->with('success', 'تم تحديث الصندوق');
    }

    public function destroy(Safe $safe)
    {
        $safe->delete();
        return redirect()->route('safes.index')->with('success', 'تم حذف الصندوق');
    }
}
