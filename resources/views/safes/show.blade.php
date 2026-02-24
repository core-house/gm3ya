@extends('layout')

@section('title', 'صندوق: ' . $safe->name)

@section('content')
<h1>{{ $safe->name }}</h1>
<a href="{{ route('safes.edit', $safe) }}" class="btn">تعديل</a>
<a href="{{ route('safes.index') }}" class="btn">رجوع</a>

<div class="card">
    <h2>معلومات الصندوق</h2>
    <p><strong>الرصيد الحالي:</strong> <span style="font-size: 24px;">{{ number_format($safe->balance, 2) }}</span></p>
    <p><strong>الوصف:</strong> {{ $safe->description }}</p>
</div>

<div class="card">
    <h2>آخر 50 حركة</h2>
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
                <td><span class="badge badge-{{ $transaction->type == 'in' ? 'success' : 'danger' }}">{{ $transaction->type == 'in' ? 'دخول' : 'خروج' }}</span></td>
                <td>{{ number_format($transaction->amount, 2) }}</td>
                <td>{{ $transaction->description }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align: center;">لا توجد حركات</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

