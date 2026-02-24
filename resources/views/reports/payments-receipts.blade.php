@extends('layout')

@section('title', 'تقرير المدفوعات والمقبوضات')

@section('content')
<h1>تقرير المدفوعات والمقبوضات</h1>
<a href="{{ route('reports.index') }}" class="btn">رجوع للتقارير</a>

<div class="card">
    <h2>اختر الفترة</h2>
    <form method="GET">
        <label>من</label>
        <input type="date" name="from" value="{{ $from ?? '' }}">
        
        <label>إلى</label>
        <input type="date" name="to" value="{{ $to ?? '' }}">
        
        <button type="submit" class="btn btn-success">عرض</button>
    </form>
</div>

@if(isset($payments))
<div class="stats">
    <div class="stat-box">
        <h3>{{ number_format($receipts, 2) }}</h3>
        <p>إجمالي المقبوضات</p>
    </div>
    <div class="stat-box">
        <h3>{{ number_format($payments, 2) }}</h3>
        <p>إجمالي المدفوعات</p>
    </div>
    <div class="stat-box" style="background: {{ $net >= 0 ? '#0f0' : '#f00' }};">
        <h3>{{ number_format($net, 2) }}</h3>
        <p>صافي الحركة</p>
    </div>
</div>

<div class="card">
    <h2>تفاصيل المقبوضات</h2>
    <table>
        <thead>
            <tr>
                <th>التاريخ</th>
                <th>العميل</th>
                <th>الصندوق</th>
                <th>المبلغ</th>
                <th>النوع</th>
            </tr>
        </thead>
        <tbody>
            @forelse($receipt_details as $receipt)
            <tr>
                <td>{{ $receipt->receipt_date->format('Y-m-d') }}</td>
                <td>{{ $receipt->client->name }}</td>
                <td>{{ $receipt->safe->name }}</td>
                <td>{{ number_format($receipt->amount, 2) }}</td>
                <td>{{ $receipt->type }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align: center;">لا توجد مقبوضات</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h2>تفاصيل المدفوعات</h2>
    <table>
        <thead>
            <tr>
                <th>التاريخ</th>
                <th>العميل</th>
                <th>الصندوق</th>
                <th>المبلغ</th>
                <th>النوع</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payment_details as $payment)
            <tr>
                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                <td>{{ $payment->client->name }}</td>
                <td>{{ $payment->safe->name }}</td>
                <td>{{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->type }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align: center;">لا توجد مدفوعات</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif
@endsection

