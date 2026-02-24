<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('created_at', 'desc')->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'rate' => 'nullable|integer',
            'national_id' => 'nullable|string|max:255',
            'work_place' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric',
            'guarantor_name' => 'nullable|string|max:255',
            'guarantor_phone' => 'nullable|string|max:255',
            'guarantor_national_id' => 'nullable|string|max:255',
            'guarantor_address' => 'nullable|string',
            'guarantor_client_id' => 'nullable|exists:clients,id',
        ]);

        Client::create($request->all());
        return redirect()->route('clients.index')->with('success', 'تم إضافة العميل');
    }

    public function show(Client $client)
    {
        $client->load([
            'associationMembers.association', 'loans', 'deposits', 'debts', 
            'payments', 'receipts', 'penalties', 'guarantor', 'guaranteedClients'
        ]);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'rate' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
            'national_id' => 'nullable|string|max:255',
            'work_place' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric',
            'guarantor_name' => 'nullable|string|max:255',
            'guarantor_phone' => 'nullable|string|max:255',
            'guarantor_national_id' => 'nullable|string|max:255',
            'guarantor_address' => 'nullable|string',
            'guarantor_client_id' => 'nullable|exists:clients,id',
        ]);

        $client->update($request->all());
        return redirect()->route('clients.index')->with('success', 'تم تحديث العميل');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'تم حذف العميل');
    }
}
