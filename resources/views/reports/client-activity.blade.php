@extends('layout')

@section('title', 'تقرير حركة العميل')

@section('content')
<h1>تقرير حركة العميل</h1>
<a href="{{ route('reports.index') }}" class="btn">رجوع للتقارير</a>

<div class="card">
    <h2>اختر العميل</h2>
    <form method="GET">
        <label>العميل</label>
        <select name="client_id" onchange="this.form.submit()">
            <option value="">اختر عميل</option>
            @foreach($clients as $c)
            <option value="{{ $c->id }}" {{ isset($client) && $client->id == $c->id ? 'selected' : '' }}>
                {{ $c->name }}
            </option>
            @endforeach
        </select>
    </form>
</div>

@if(isset($client))
<div class="card">
    <h2>{{ $client->name }}</h2>
    <p><strong>الهاتف:</strong> {{ $client->phone }}</p>
    <p><strong>التقييم:</strong> {{ $client->rate }}</p>
    <p><strong>الحالة:</strong> <span class="badge badge-{{ $client->status }}">{{ $client->status }}</span></p>
</div>

<div class="card">
    <h2>الجمعيات</h2>
    <table>
        <thead>
            <tr>
                <th>الجمعية</th>
                <th>المبلغ الشهري</th>
                <th>رقم الدور</th>
                <th>تاريخ الاستحقاق</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($client->associationMembers as $member)
            <tr>
                <td>{{ $member->association->name }}</td>
                <td>{{ number_format($member->association->monthly_amount, 2) }}</td>
                <td>{{ $member->turn_number }}</td>
                <td>{{ $member->due_date->format('Y-m-d') }}</td>
                <td><span class="badge badge-{{ $member->collection_status }}">{{ $member->collection_status }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align: center;">لا توجد جمعيات</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h2>السلف</h2>
    <table>
        <thead>
            <tr>
                <th>المبلغ</th>
                <th>التاريخ</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($client->loans as $loan)
            <tr>
                <td>{{ number_format($loan->amount, 2) }}</td>
                <td>{{ $loan->loan_date->format('Y-m-d') }}</td>
                <td><span class="badge badge-{{ $loan->status }}">{{ $loan->status }}</span></td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align: center;">لا توجد سلف</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h2>الأمانات</h2>
    <table>
        <thead>
            <tr>
                <th>المبلغ</th>
                <th>التاريخ</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($client->deposits as $deposit)
            <tr>
                <td>{{ number_format($deposit->amount, 2) }}</td>
                <td>{{ $deposit->deposit_date->format('Y-m-d') }}</td>
                <td><span class="badge badge-{{ $deposit->status }}">{{ $deposit->status }}</span></td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align: center;">لا توجد أمانات</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h2>الديون</h2>
    <table>
        <thead>
            <tr>
                <th>المبلغ</th>
                <th>النوع</th>
                <th>تاريخ الاستحقاق</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($client->debts as $debt)
            <tr>
                <td>{{ number_format($debt->amount, 2) }}</td>
                <td>{{ $debt->type == 'temporary' ? 'مؤقت' : 'دائم' }}</td>
                <td>{{ $debt->due_date->format('Y-m-d') }}</td>
                <td><span class="badge badge-{{ $debt->status }}">{{ $debt->status }}</span></td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align: center;">لا توجد ديون</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="stats">
    <div class="stat-box">
        <h3>{{ number_format($client->payments->sum('amount'), 2) }}</h3>
        <p>إجمالي المدفوعات</p>
    </div>
    <div class="stat-box">
        <h3>{{ number_format($client->receipts->sum('amount'), 2) }}</h3>
        <p>إجمالي المقبوضات</p>
    </div>
</div>
@endif
@endsection

