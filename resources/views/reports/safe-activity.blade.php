@extends('layout')

@section('title', 'تقرير حركة الصندوق')

@section('content')
<h1>تقرير حركة الصندوق</h1>
<a href="{{ route('reports.index') }}" class="btn">رجوع للتقارير</a>

<div class="card">
    <h2>اختر الصندوق</h2>
    <form method="GET">
        <label>الصندوق</label>
        <select name="safe_id" onchange="this.form.submit()">
            <option value="">اختر صندوق</option>
            @foreach($safes as $s)
            <option value="{{ $s->id }}" {{ isset($safe) && $safe->id == $s->id ? 'selected' : '' }}>
                {{ $s->name }}
            </option>
            @endforeach
        </select>
    </form>
</div>

@if(isset($safe))
<div class="card">
    <h2>{{ $safe->name }}</h2>
    <p><strong>الرصيد الحالي:</strong> <span style="font-size: 24px;">{{ number_format($safe->balance, 2) }}</span></p>
    <p><strong>الوصف:</strong> {{ $safe->description }}</p>
</div>

<div class="stats">
    <div class="stat-box" style="background: #0f0;">
        <h3>{{ number_format($total_in, 2) }}</h3>
        <p>إجمالي الدخول</p>
    </div>
    <div class="stat-box" style="background: #f00; color: #fff;">
        <h3>{{ number_format($total_out, 2) }}</h3>
        <p>إجمالي الخروج</p>
    </div>
    <div class="stat-box">
        <h3>{{ number_format($total_in - $total_out, 2) }}</h3>
        <p>الفرق</p>
    </div>
</div>

<div class="card">
    <h2>جميع الحركات</h2>
    <table>
        <thead>
            <tr>
                <th>التاريخ</th>
                <th>النوع</th>
                <th>المبلغ</th>
                <th>الوصف</th>
            </tr>
        </thead>
        <tbody>
            @forelse($safe->transactions as $transaction)
            <tr>
                <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                <td>
                    <span class="badge" style="background: {{ $transaction->type == 'in' ? '#0f0' : '#f00' }}; {{ $transaction->type == 'out' ? 'color: #fff;' : '' }}">
                        {{ $transaction->type == 'in' ? 'دخول' : 'خروج' }}
                    </span>
                </td>
                <td><strong>{{ number_format($transaction->amount, 2) }}</strong></td>
                <td>{{ $transaction->description }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align: center;">لا توجد حركات</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif
@endsection

