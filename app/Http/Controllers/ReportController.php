<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Client;
use App\Models\Safe;
use App\Models\Transaction;
use App\Models\AssociationMember;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function paymentsReceipts(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to = $request->to ?? now()->endOfMonth()->format('Y-m-d');

        $payments = Payment::whereBetween('payment_date', [$from, $to])->sum('amount');
        $receipts = Receipt::whereBetween('receipt_date', [$from, $to])->sum('amount');
        $net = $receipts - $payments;

        $payment_details = Payment::with(['client', 'safe'])
            ->whereBetween('payment_date', [$from, $to])
            ->orderBy('payment_date', 'desc')
            ->get();

        $receipt_details = Receipt::with(['client', 'safe'])
            ->whereBetween('receipt_date', [$from, $to])
            ->orderBy('receipt_date', 'desc')
            ->get();

        return view('reports.payments-receipts', compact('payments', 'receipts', 'net', 'payment_details', 'receipt_details', 'from', 'to'));
    }

    public function clientActivity(Request $request)
    {
        $clients = Client::all();
        $client_id = $request->client_id;

        if (!$client_id) {
            return view('reports.client-activity', compact('clients'));
        }

        $client = Client::with([
            'associationMembers.association',
            'loans',
            'deposits',
            'debts',
            'payments',
            'receipts'
        ])->findOrFail($client_id);

        return view('reports.client-activity', compact('clients', 'client'));
    }

    public function safeActivity(Request $request)
    {
        $safes = Safe::all();
        $safe_id = $request->safe_id;

        if (!$safe_id) {
            return view('reports.safe-activity', compact('safes'));
        }

        $safe = Safe::with(['transactions' => function($q) {
            $q->orderBy('transaction_date', 'desc');
        }])->findOrFail($safe_id);

        $total_in = Transaction::where('safe_id', $safe_id)->where('type', 'in')->sum('amount');
        $total_out = Transaction::where('safe_id', $safe_id)->where('type', 'out')->sum('amount');

        return view('reports.safe-activity', compact('safes', 'safe', 'total_in', 'total_out'));
    }

    public function upcomingCompletions(Request $request)
    {
        $months = $request->months ?? 3;

        $upcoming = AssociationMember::with(['client', 'association'])
            ->where('collection_status', 'pending')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addMonths($months))
            ->orderBy('due_date', 'asc')
            ->get();

        return view('reports.upcoming-completions', compact('upcoming', 'months'));
    }

    public function liquidity()
    {
        // الرصيد الفعلي في الصناديق
        $actual_cash = Safe::sum('balance');

        // المستحق علينا (الأدوار القادمة في الجمعيات النشطة)
        $upcoming_obligations = AssociationMember::with('association')
            ->whereHas('association', function($q) {
                $q->where('status', 'active');
            })
            ->where('collection_status', 'pending')
            ->get()
            ->sum(function($member) {
                return $member->association->total_amount;
            });

        // الأمانات النشطة (لازم نردها)
        $active_deposits = \App\Models\Deposit::where('status', 'active')->sum('amount');

        // المستحق لنا (ديون + سلف نشطة)
        $receivables_debts = \App\Models\Debt::where('status', 'pending')->sum('amount');
        $receivables_loans = \App\Models\Loan::where('status', 'active')->sum('amount');
        $total_receivables = $receivables_debts + $receivables_loans;

        // صافي السيولة المتاحة
        $net_liquidity = $actual_cash - $upcoming_obligations - $active_deposits + $total_receivables;

        // التأمينات المحصلة (ممكن نستخدمها في الطوارئ)
        $collected_insurance = AssociationMember::where('insurance_status', 'paid')->sum('insurance_amount');

        // الغرامات المعلقة (ممكن تتحصل)
        $pending_penalties = \App\Models\Penalty::where('status', 'pending')->sum('amount');

        return view('reports.liquidity', compact(
            'actual_cash', 'upcoming_obligations', 'active_deposits', 
            'total_receivables', 'net_liquidity', 'collected_insurance', 'pending_penalties'
        ));
    }

    public function riskyClients()
    {
        // العملاء المتعثرين
        $risky_clients = Client::with(['debts' => function($q) {
            $q->where('status', 'pending');
        }, 'penalties' => function($q) {
            $q->where('status', 'pending');
        }, 'loans' => function($q) {
            $q->where('status', 'active');
        }])
        ->where('status', 'active')
        ->get()
        ->filter(function($client) {
            return $client->debts->count() > 0 || 
                   $client->penalties->where('status', 'pending')->count() > 1 ||
                   $client->loans->where('status', 'active')->sum('amount') > ($client->salary * 2);
        })
        ->sortByDesc(function($client) {
            return $client->debts->sum('amount') + $client->penalties->sum('amount');
        });

        return view('reports.risky-clients', compact('risky_clients'));
    }
}
