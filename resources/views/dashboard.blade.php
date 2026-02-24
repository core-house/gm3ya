@extends('layout')

@section('title', 'الرئيسية')

@section('content')
<h1>لوحة التحكم</h1>

<div class="stats">
    <div class="stat-box">
        <h3>{{ $stats['clients'] }}</h3>
        <p>عميل نشط</p>
    </div>
    <div class="stat-box">
        <h3>{{ $stats['associations'] }}</h3>
        <p>جمعية نشطة</p>
    </div>
    <div class="stat-box">
        <h3>{{ number_format($stats['safes_balance'], 2) }}</h3>
        <p>رصيد الصناديق</p>
    </div>
    <div class="stat-box">
        <h3>{{ number_format($stats['active_loans'], 2) }}</h3>
        <p>سلف نشطة</p>
    </div>
    <div class="stat-box">
        <h3>{{ number_format($stats['active_deposits'], 2) }}</h3>
        <p>أمانات نشطة</p>
    </div>
    <div class="stat-box">
        <h3>{{ number_format($stats['pending_debts'], 2) }}</h3>
        <p>ديون معلقة</p>
    </div>
</div>

<div class="card">
    <h2>الصناديق</h2>
    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>الرصيد</th>
            </tr>
        </thead>
        <tbody>
            @foreach($safes as $safe)
            <tr>
                <td><a href="{{ route('safes.show', $safe) }}">{{ $safe->name }}</a></td>
                <td>{{ number_format($safe->balance, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card">
    <h2>الأدوار القادمة (الشهر الحالي)</h2>
    <table>
        <thead>
            <tr>
                <th>الجمعية</th>
                <th>العميل</th>
                <th>رقم الدور</th>
                <th>تاريخ الاستحقاق</th>
            </tr>
        </thead>
        <tbody>
            @forelse($upcoming_turns as $turn)
            <tr>
                <td>{{ $turn->association->name }}</td>
                <td>{{ $turn->client->name }}</td>
                <td>{{ $turn->turn_number }}</td>
                <td>{{ $turn->due_date->format('Y-m-d') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">لا توجد أدوار قادمة</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

