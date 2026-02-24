<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Association;
use App\Models\Safe;
use App\Models\Loan;
use App\Models\Deposit;
use App\Models\Debt;
use App\Models\AssociationMember;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'clients' => Client::where('status', 'active')->count(),
            'associations' => Association::where('status', 'active')->count(),
            'safes_balance' => Safe::sum('balance'),
            'active_loans' => Loan::where('status', 'active')->sum('amount'),
            'active_deposits' => Deposit::where('status', 'active')->sum('amount'),
            'pending_debts' => Debt::where('status', 'pending')->sum('amount'),
        ];

        $safes = Safe::all();
        $active_associations = Association::where('status', 'active')->with('members')->get();
        
        // العملاء اللي دورهم قريب
        $upcoming_turns = AssociationMember::with(['client', 'association'])
            ->where('collection_status', 'pending')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addMonth())
            ->orderBy('due_date', 'asc')
            ->get();

        return view('dashboard', compact('stats', 'safes', 'active_associations', 'upcoming_turns'));
    }
}
