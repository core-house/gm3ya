<?php

namespace App\Http\Controllers;

use App\Models\Association;
use App\Models\Client;
use App\Models\AssociationMember;
use Illuminate\Http\Request;

class AssociationController extends Controller
{
    public function index(Request $request)
    {
        $query = Association::withCount('members as actual_members_count');
        
        // فلتر البحث بالاسم
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        // فلتر الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // فلتر تاريخ البداية
        if ($request->filled('start_from')) {
            $query->where('start_date', '>=', $request->start_from);
        }
        if ($request->filled('start_to')) {
            $query->where('start_date', '<=', $request->start_to);
        }
        
        // فلتر تاريخ النهاية (آخر due_date من association_members)
        if ($request->filled('end_from')) {
            $query->whereRaw('(SELECT MAX(due_date) FROM association_members WHERE association_id = associations.id) >= ?', [$request->end_from]);
        }
        if ($request->filled('end_to')) {
            $query->whereRaw('(SELECT MAX(due_date) FROM association_members WHERE association_id = associations.id) <= ?', [$request->end_to]);
        }
        
        $associations = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->through(function($association) {
                $lastMember = $association->members()->orderBy('due_date', 'desc')->first();
                $association->end_date = $lastMember ? $lastMember->due_date : null;
                return $association;
            });
        
        // إضافة الفلاتر للـ pagination links
        $associations->appends($request->query());
        
        return view('associations.index', compact('associations'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        return view('associations.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'monthly_amount' => 'required|numeric|min:0',
            'members_count' => 'required|integer|min:2',
            'start_date' => 'required|date',
            'client_ids' => 'nullable|array',
            'client_ids.*' => 'exists:clients,id',
            'insurance_amount' => 'nullable|numeric|min:0',
            'commission_amount' => 'nullable|numeric|min:0',
            'commission_type' => 'required|in:percentage,fixed',
        ]);

        $total_amount = $request->monthly_amount * $request->members_count;

        $association = Association::create([
            'name' => $request->name,
            'monthly_amount' => $request->monthly_amount,
            'members_count' => $request->members_count,
            'total_amount' => $total_amount,
            'insurance_amount' => $request->insurance_amount ?? 0,
            'commission_amount' => $request->commission_amount ?? 0,
            'commission_type' => $request->commission_type,
            'start_date' => $request->start_date,
            'status' => 'active',
        ]);

        // إضافة الأعضاء إذا تم اختيارهم
        if ($request->has('client_ids') && !empty($request->client_ids)) {
            $insurance_per_member = $request->insurance_amount > 0 
                ? $request->insurance_amount / count($request->client_ids) 
                : 0;

            foreach ($request->client_ids as $index => $client_id) {
                AssociationMember::create([
                    'association_id' => $association->id,
                    'client_id' => $client_id,
                    'turn_number' => $index + 1,
                    'due_date' => date('Y-m-d', strtotime($request->start_date . ' +' . $index . ' months')),
                    'collection_status' => 'pending',
                    'insurance_amount' => $insurance_per_member,
                    'insurance_status' => 'pending',
                ]);
            }
        }

        return redirect()->route('associations.index')->with('success', 'تم إضافة الجمعية');
    }

    public function show(Association $association)
    {
        $association->load(['members.client']);
        $safes = \App\Models\Safe::all();
        return view('associations.show', compact('association', 'safes'));
    }

    public function edit(Association $association)
    {
        $clients = Client::where('status', 'active')->get();
        $association->load('members');
        return view('associations.edit', compact('association', 'clients'));
    }

    public function update(Request $request, Association $association)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        $association->update($request->only(['name', 'status']));
        return redirect()->route('associations.index')->with('success', 'تم تحديث الجمعية');
    }

    public function destroy(Association $association)
    {
        $association->delete();
        return redirect()->route('associations.index')->with('success', 'تم حذف الجمعية');
    }

    public function collect(Request $request, AssociationMember $member)
    {
        $request->validate([
            'safe_id' => 'required|exists:safes,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // إنشاء payment
        $payment = \App\Models\Payment::create([
            'client_id' => $member->client_id,
            'safe_id' => $request->safe_id,
            'amount' => $request->amount,
            'type' => 'association',
            'reference_id' => $member->association_id,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes,
        ]);

        // خصم من الصندوق
        $safe = \App\Models\Safe::find($request->safe_id);
        $safe->balance -= $request->amount;
        $safe->save();

        // تسجيل في الحركات
        \App\Models\Transaction::create([
            'safe_id' => $request->safe_id,
            'type' => 'out',
            'amount' => $request->amount,
            'description' => 'دفعة للعميل: ' . $member->client->name . ' - جمعية: ' . $member->association->name,
            'transaction_date' => $request->payment_date,
        ]);

        // تحديث حالة القبض
        $member->update(['collection_status' => 'collected']);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل القبض بنجاح'
            ]);
        }

        return back()->with('success', 'تم تسجيل القبض');
    }

    public function forceEnd(Request $request, AssociationMember $member)
    {
        $member->update(['collection_status' => 'forced_end']);
        return back()->with('success', 'تم الإنهاء القصري');
    }

    /**
     * Show form to edit turns/members.
     */
    public function editTurns(Association $association)
    {
        $association->load(['members.client']);
        $clients = Client::where('status', 'active')->get();
        return view('associations.edit-turns', compact('association', 'clients'));
    }

    /**
     * Update turns/members.
     */
    public function updateTurns(Request $request, Association $association)
    {
        $request->validate([
            'members' => 'required|array',
            'members.*.id' => 'required|exists:association_members,id',
            'members.*.client_id' => 'required|exists:clients,id',
            'members.*.turn_number' => 'required|integer|min:1',
            'members.*.due_date' => 'required|date',
        ]);

        foreach ($request->members as $memberData) {
            AssociationMember::where('id', $memberData['id'])
                ->where('association_id', $association->id)
                ->update([
                    'client_id' => $memberData['client_id'],
                    'turn_number' => $memberData['turn_number'],
                    'due_date' => $memberData['due_date'],
                ]);
        }

        return redirect()->route('associations.show', $association)
            ->with('success', 'تم تحديث الأدوار بنجاح');
    }

    /**
     * Add new member to association.
     */
    public function addMember(Request $request, Association $association)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'turn_number' => 'required|integer|min:1',
            'due_date' => 'required|date',
        ]);

        $insurance_per_member = $association->insurance_amount > 0 
            ? $association->insurance_amount / ($association->members_count > 0 ? $association->members_count : 1)
            : 0;

        $member = AssociationMember::create([
            'association_id' => $association->id,
            'client_id' => $request->client_id,
            'turn_number' => $request->turn_number,
            'due_date' => $request->due_date,
            'collection_status' => 'pending',
            'insurance_amount' => $insurance_per_member,
            'insurance_status' => 'pending',
        ]);

        $member->load('client');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة العضو بنجاح',
                'member' => [
                    'id' => $member->id,
                    'client_id' => $member->client_id,
                    'client_name' => $member->client->name,
                    'turn_number' => $member->turn_number,
                    'due_date' => $member->due_date->format('Y-m-d'),
                ]
            ]);
        }

        return redirect()->route('associations.show', $association)
            ->with('success', 'تم إضافة العضو بنجاح');
    }

    /**
     * Delete member from association.
     */
    public function deleteMember(AssociationMember $member)
    {
        $association = $member->association;
        $member->delete();
        return redirect()->route('associations.show', $association)
            ->with('success', 'تم حذف العضو بنجاح');
    }
}
